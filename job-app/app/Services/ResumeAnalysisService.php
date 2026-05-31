<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

use OpenAI\Laravel\Facades\OpenAI;

class ResumeAnalysisService
{
    public function extractResumeInformation(string $fileUrl): array
    {
        try {
            Log::debug("Starting PDF text extraction from URL: {$fileUrl}");

            // Extract raw text from the resume pdf file
            $rawText = $this->extractTextFromPdfByUrl($fileUrl);

            // Log successful text extraction
            Log::debug("Successfully extracted text from PDF file. String length: " . \strlen($rawText) . " characters");

            // Use OpenAI API to organize the text into a structured format
            Log::debug("Starting OpenAI parsing...");
            $result = $this->parseWithOpenAI($rawText);

            Log::debug("OpenAI parsing completed successfully");
            return $result;

        } catch (\Exception $e) {
            Log::error("Error extracting resume information: " . $e->getMessage());
            return [
                'summary' => '',
                'skills' => '',
                'experience' => '',
                'education' => ''
            ];
        }
    }

    public function extractTextFromPdf(UploadedFile $file): string
    {
        try {
            // WSL'in erişebileceği temp dizini kullan
            $wslTempDir = '/tmp';
            $inputFileName = 'pdf_input_' . uniqid() . '.pdf';
            $outputFileName = 'pdf_output_' . uniqid() . '.txt';

            $wslInputPath = "{$wslTempDir}/{$inputFileName}";
            $wslOutputPath = "{$wslTempDir}/{$outputFileName}";

            // Dosyayı Windows temp'e kopyala, sonra WSL'e taşı
            $windowsTempFile = tempnam(sys_get_temp_dir(), 'pdf_');
            copy($file->getPathname(), $windowsTempFile);

            // Windows path'ini WSL path'ine çevir
            $windowsPath = str_replace('\\', '/', $windowsTempFile);
            $wslWindowsPath = str_replace('C:/', '/mnt/c/', $windowsPath);

            Log::debug("Windows temp file: {$windowsTempFile}");
            Log::debug("WSL Windows path: {$wslWindowsPath}");

            // Dosyayı WSL temp dizinine kopyala
            $copyCommand = "wsl cp '{$wslWindowsPath}' '{$wslInputPath}'";
            Log::debug("Copy command: {$copyCommand}");

            $copyResult = shell_exec($copyCommand . ' 2>&1');
            Log::debug("Copy result: " . ($copyResult ?: 'success'));

            // PDF'den metin çıkar
            $extractCommand = "wsl /usr/bin/pdftotext {$wslInputPath} {$wslOutputPath}";
            Log::debug("PDF extraction command: {$extractCommand}");

            $result = shell_exec($extractCommand . ' 2>&1');

            // Sonucu oku
            $readCommand = "wsl cat {$wslOutputPath}";
            $text = shell_exec($readCommand);

            // Temp dosyalarını temizle
            shell_exec("wsl rm -f {$wslInputPath} {$wslOutputPath}");
            if (file_exists($windowsTempFile)) {
                unlink($windowsTempFile);
            }

            if (!empty($text)) {
                $cleanedText = $this->cleanText($text);
                Log::debug("Successfully extracted text from PDF file. String length: " . strlen($cleanedText) . " characters");
                return $cleanedText;
            }

            throw new \Exception("PDF text extraction failed: {$result}");

        } catch (\Exception $e) {
            Log::error("PDF metin çıkarma hatası: " . $e->getMessage());
            throw new \Exception("PDF metin çıkarma hatası: " . $e->getMessage());
        }
    }

    private function extractTextFromPdfByUrl(string $fileUrl): string
    {
        try {
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            if (!$filePath) {
                throw new \Exception('Invalid file URL');
            }

            $filename = basename($filePath);
            $storagePath = "resumes/{$filename}";

            if (!Storage::disk('cloud')->exists($storagePath)) {
                throw new \Exception('File not found');
            }

            $pdfContent = Storage::disk('cloud')->get($storagePath);
            if (!$pdfContent) {
                throw new \Exception('Failed to read file');
            }

            // WSL'in erişebileceği temp dizini kullan
            $wslTempDir = '/tmp';
            $inputFileName = 'pdf_input_' . uniqid() . '.pdf';
            $outputFileName = 'pdf_output_' . uniqid() . '.txt';

            $wslInputPath = "{$wslTempDir}/{$inputFileName}";
            $wslOutputPath = "{$wslTempDir}/{$outputFileName}";

            // Dosyayı Windows temp'e yaz, sonra WSL'e kopyala
            $windowsTempFile = tempnam(sys_get_temp_dir(), 'pdf_');
            file_put_contents($windowsTempFile, $pdfContent);

            // Windows dosyasını WSL'e kopyala
            $copyCommand = "wsl cp /mnt/c" . str_replace(['C:', '\\'], ['', '/'], $windowsTempFile) . " {$wslInputPath}";

            Log::debug("Copy command: {$copyCommand}");
            shell_exec($copyCommand);

            // PDF'den metin çıkar
            $extractCommand = "wsl /usr/bin/pdftotext {$wslInputPath} {$wslOutputPath}";
            Log::debug("PDF extraction command: {$extractCommand}");

            $result = shell_exec($extractCommand . ' 2>&1');

            // Sonucu oku
            $readCommand = "wsl cat {$wslOutputPath}";
            $text = shell_exec($readCommand);

            // Temp dosyalarını temizle
            shell_exec("wsl rm -f {$wslInputPath} {$wslOutputPath}");
            if (file_exists($windowsTempFile)) {
                unlink($windowsTempFile);
            }

            if (!empty($text)) {
                $cleanedText = $this->cleanText($text);
                Log::debug("Successfully extracted text from PDF file. String length: " . strlen($cleanedText) . " characters");
                return $cleanedText;
            }

            throw new \Exception("PDF text extraction failed: {$result}");

        } catch (\Exception $e) {
            Log::error("PDF URL extraction error: " . $e->getMessage());
            throw $e;
        }
    }

    private function parseWithOpenAI(string $rawText): array
    {
        try {
            // Use OpenAI API to organize the text into a structured format
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a precise resume parser. Extract information exactly as it appears in the resume without adding any interpretation or additional information. The output should be in JSON format.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Parse the following resume content and extract the information as a JSON Object with the exact keys: 'summary', 'skills', 'experience', 'education'. The resume content is: {$rawText}. Return an empty string for any key that is not found."
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.1
            ]);

            $result = $response->choices[0]->message->content;
            Log::debug("OpenAI response: " . $result);

            $parsedResult = json_decode($result, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse OpenAI response: ' . json_last_error_msg());
                throw new \Exception('Failed to parse OpenAI response');
            }

            // Validate the parsed result
            $requiredKeys = ['summary', 'skills', 'experience', 'education'];
            $missingKeys = array_diff($requiredKeys, array_keys($parsedResult));

            if (\count($missingKeys) > 0) {
                Log::error('Missing required keys: ' . implode(', ', $missingKeys));
                throw new \Exception('Missing required keys in the parsed result');
            }

            // Return the JSON object
            return [
                'summary' => is_string($parsedResult['summary']) ? $parsedResult['summary'] : (is_array($parsedResult['summary']) ? implode(', ', $parsedResult['summary']) : ''),
                'skills' => is_string($parsedResult['skills']) ? $parsedResult['skills'] : (is_array($parsedResult['skills']) ? implode(', ', $parsedResult['skills']) : ''),
                'experience' => is_string($parsedResult['experience']) ? $parsedResult['experience'] : (is_array($parsedResult['experience']) ? json_encode($parsedResult['experience']) : ''),
                'education' => is_string($parsedResult['education']) ? $parsedResult['education'] : (is_array($parsedResult['education']) ? json_encode($parsedResult['education']) : '')
            ];

        } catch (\Exception $e) {
            Log::error('Error parsing with OpenAI: ' . $e->getMessage());
            throw $e;
        }
    }

    public function analyzeResume($jobVacancy, $resumeData): array
    {
        try {
            $jobDetails = json_encode([
                'job_title' => $jobVacancy->title,
                'job_description' => $jobVacancy->description,
                'job_location' => $jobVacancy->location,
                'job_type' => $jobVacancy->type,
                'job_salary' => $jobVacancy->salary,
            ]);

            $resumeDetails = json_encode($resumeData);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert HR professional and job recruiter. You are given a job vacancy and a resume. Your task is to analyze the resume and determine if the candidate is a good fit for the job.
                         The output should be in JSON format. Provide a score from 0 to 100 for the candidate's suitability for the job, and a detailed feedback. Response should only be Json that has the following keys: 'aiGeneratedScore', 'aiGeneratedFeedback'. AI generated feedback should be detailed and specific to the job and the candidate's resume."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Please evaluate this job application. Job Details: {$jobDetails}. Resume Details: {$resumeDetails}"
                    ]
                ],
                'response_format' => [
                    'type' => 'json_object'
                ],
                'temperature' => 0.1
            ]);

            $result = $response->choices[0]->message->content;
            Log::debug("OpenAI evaluation response: {$result}");

            $parsedResult = json_decode($result, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse OpenAI response: ' . json_last_error_msg());
                throw new \Exception('Failed to parse OpenAI response');
            }

            if (!isset($parsedResult['aiGeneratedScore']) || !isset($parsedResult['aiGeneratedFeedback'])) {
                Log::error('Missing required keys in the parsed result');
                throw new \Exception('Missing required keys in the parsed result');
            }

            return $parsedResult;

        } catch (\Exception $e) {
            Log::error('Error analyzing resume: ' . $e->getMessage());
            return [
                'aiGeneratedScore' => 0,
                'aiGeneratedFeedback' => 'An error occurred while analyzing the resume. Please try again later.'
            ];
        }
    }

    public function analyzeResumeText(string $text): array
    {
        return [
            'skills' => $this->extractSkills($text),
            'experience' => $this->extractExperience($text),
            'education' => $this->extractEducation($text),
            'contact' => $this->extractContactInfo($text),
            'summary' => $this->generateSummary($text)
        ];
    }

    private function cleanText(string $text): string
    {
        // İlk olarak mevcut encoding'i kontrol et
        $currentEncoding = mb_detect_encoding($text, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);

        // Eğer UTF-8 değilse, UTF-8'e çevir
        if ($currentEncoding && $currentEncoding !== 'UTF-8') {
            $text = mb_convert_encoding($text, 'UTF-8', $currentEncoding);
        }

        // UTF-8 olmayan karakterleri temizle - daha agresif yaklaşım
        $text = @iconv('UTF-8', 'UTF-8//IGNORE', $text);

        // Eğer iconv başarısız olursa, alternatif yöntem
        if ($text === false) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        // Kontrol karakterlerini ve geçersiz UTF-8 karakterlerini kaldır
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F]/', '', $text);

        // Sadece yazdırılabilir karakterleri ve temel whitespace'leri tut
        $text = preg_replace('/[^\x20-\x7E\x0A\x0D\xC0-\xFF]/', '', $text);

        // Çoklu boşlukları tek boşluğa çevir
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Çok uzun metinleri kısalt (database limit için)
        if (\strlen($text) > 60000) {
            $text = substr($text, 0, 60000) . '...';
        }

        // Son kontrol - sadece geçerli UTF-8 karakterler
        if (!mb_check_encoding($text, 'UTF-8')) {
            // Eğer hala geçersiz karakterler varsa, sadece ASCII karakterleri tut
            $text = preg_replace('/[^\x20-\x7E\s]/', '', $text);
        }

        return $text;
    }

    private function extractSkills(string $text): array
    {
        $skills = [];

        // Yaygın teknoloji ve beceri kelimeleri
        $skillKeywords = [
            'PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'Node.js',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
            'HTML', 'CSS', 'Bootstrap', 'Tailwind',
            'Git', 'Docker', 'AWS', 'Linux',
            'Python', 'Java', 'C#', '.NET'
        ];

        foreach ($skillKeywords as $skill) {
            if (stripos($text, $skill) !== false) {
                $skills[] = $skill;
            }
        }

        return array_unique($skills);
    }

    private function extractExperience(string $text): array
    {
        $experience = [];

        // Deneyim bölümünü bul
        if (preg_match('/(?:experience|deneyim|iş tecrübesi)(.*?)(?:education|eğitim|skills|beceriler|$)/is', $text, $matches)) {
            $experienceText = $matches[1];

            // Yıl aralıklarını bul (2020-2023, 2020-present vb.)
            preg_match_all('/(\d{4})\s*[-–]\s*(\d{4}|present|günümüz)/i', $experienceText, $dateMatches);

            if (!empty($dateMatches[0])) {
                foreach ($dateMatches[0] as $dateRange) {
                    $experience[] = [
                        'period' => $dateRange,
                        'description' => 'İş deneyimi bulundu'
                    ];
                }
            }
        }

        return $experience;
    }

    private function extractEducation(string $text): array
    {
        $education = [];

        // Eğitim kelimeleri
        $educationKeywords = [
            'university', 'üniversite', 'college', 'degree', 'bachelor', 'master',
            'lisans', 'yüksek lisans', 'doktora', 'mezun'
        ];

        foreach ($educationKeywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                $education[] = [
                    'type' => $keyword,
                    'found' => true
                ];
            }
        }

        return $education;
    }

    private function extractContactInfo(string $text): array
    {
        $contact = [];

        // Email adresi
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $emailMatch)) {
            $contact['email'] = $emailMatch[0];
        }

        // Telefon numarası (Türkiye formatları)
        if (preg_match('/(?:\+90|0)?\s*[5][0-9]{2}\s*[0-9]{3}\s*[0-9]{2}\s*[0-9]{2}/', $text, $phoneMatch)) {
            $contact['phone'] = $phoneMatch[0];
        }

        return $contact;
    }

    private function generateSummary(string $text): string
    {
        $wordCount = str_word_count($text);
        $summary = "CV analizi tamamlandı. ";
        $summary .= "Toplam {$wordCount} kelime analiz edildi. ";

        return $summary;
    }
}
