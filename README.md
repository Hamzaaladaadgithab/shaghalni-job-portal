# AI Destekli İş İlanı Platformu

## Proje Hakkında

**AI Destekli İş İlanı Platformu**, iş arayan kullanıcılar ile şirketleri aynı sistem üzerinde buluşturan, Laravel 12 tabanlı bir iş ilanı ve başvuru yönetim sistemidir.

Bu projede amaç yalnızca klasik bir iş ilanı platformu geliştirmek değildir. Sistem aynı zamanda kullanıcıların yüklediği CV dosyalarını analiz ederek adayın ilgili iş ilanına uygunluğunu yapay zekâ desteğiyle değerlendirmektedir.

Proje kapsamında iş arayan kullanıcılar iş ilanlarını görüntüleyebilir, CV yükleyebilir ve başvuru yapabilir. Şirket sahipleri kendi şirketlerine ait iş ilanlarını ve başvuruları yönetebilir. Admin ise sistemin tamamını kontrol edebilir.

Bu yapı sayesinde proje; kullanıcı arayüzü, backend, veritabanı, yapay zekâ bileşeni, harici API entegrasyonu ve yönetim paneli bulunan kapsamlı bir yazılım sistemi olarak geliştirilmiştir.

---

## Proje Başlığı

**AI Destekli İş İlanı Platformu**

---

## Geliştirici

**Hamza ALadaad**
**Öğrenci No:** 220541611

---

## Danışman

**Doç. Dr. Ferhat UÇAR**
Fırat Üniversitesi
Teknoloji Fakültesi
Yazılım Mühendisliği Bölümü

---

## Projenin Amacı

Bu projenin temel amacı, iş arayan kullanıcıların iş ilanlarına daha kolay başvurmasını ve şirketlerin başvuruları daha düzenli şekilde yönetmesini sağlamaktır.

Ayrıca OpenAI API entegrasyonu sayesinde CV içerikleri analiz edilmekte, aday bilgileri yapılandırılmakta ve adayın iş ilanına uygunluğu yapay zekâ tarafından değerlendirilmektedir.

Projenin temel hedefleri şunlardır:

* İş arayanlar için kullanıcı dostu bir iş ilanı ve başvuru platformu oluşturmak.
* Şirket sahipleri için iş ilanı ve başvuru yönetim paneli geliştirmek.
* Admin için tüm sistemi yönetebilen merkezi bir backoffice paneli sunmak.
* CV dosyalarını analiz ederek aday bilgilerini çıkarmak.
* CV ve iş ilanı bilgilerini karşılaştırarak AI score ve AI feedback üretmek.
* Veritabanı, backend, arayüz ve yapay zekâ entegrasyonunu bir araya getiren tam bir yazılım sistemi geliştirmek.

---

## Sistem Mimarisi

Proje, Laravel MVC mimarisi üzerine kurulmuş çok uygulamalı bir yapıya sahiptir.

Projede üç ana bileşen bulunmaktadır:

```text
job-app
job-backoffice
job-shared
```

### job-app

`job-app`, iş arayan kullanıcıların kullandığı Laravel uygulamasıdır.

Bu uygulamada job-seeker rolündeki kullanıcılar:

* Sisteme kayıt olabilir.
* Giriş yapabilir.
* İş ilanlarını görüntüleyebilir.
* İş ilanı detaylarını inceleyebilir.
* CV yükleyebilir.
* İş başvurusu yapabilir.
* Başvurularını takip edebilir.
* AI score ve AI feedback sonuçlarını görüntüleyebilir.

### job-backoffice

`job-backoffice`, admin ve company-owner rollerinin kullandığı yönetim panelidir.

Bu panelde admin:

* Şirketleri yönetebilir.
* Kullanıcıları yönetebilir.
* İş kategorilerini yönetebilir.
* Tüm iş ilanlarını yönetebilir.
* Tüm başvuruları görüntüleyebilir.
* Sistem istatistiklerini takip edebilir.

Company-owner ise:

* Kendi şirket bilgilerini görüntüleyebilir ve düzenleyebilir.
* Kendi şirketine ait iş ilanlarını yönetebilir.
* Kendi ilanlarına gelen başvuruları görüntüleyebilir.
* Başvuru durumlarını güncelleyebilir.

### job-shared

`job-shared`, iki Laravel uygulaması tarafından ortak kullanılan Eloquent modellerini içeren paylaşımlı yapıdır.

Bu yapı sayesinde `job-app` ve `job-backoffice` aynı modelleri ortak kullanır. Böylece tekrar kod yazımı azaltılmış, veri modeli tutarlılığı sağlanmış ve iki uygulama arasında ortak bir veri erişim katmanı oluşturulmuştur.

Ortak kullanılan temel modeller:

```text
User
Company
JobCategory
JobVacancy
Resume
JobApplication
```

---

## Kullanılan Mimari Yaklaşım

Projede temel olarak **Laravel MVC mimarisi** kullanılmıştır.

MVC yapısı şu şekilde çalışır:

* **Model:** Veritabanı tablolarını temsil eder.
* **View:** Kullanıcının gördüğü Blade arayüz dosyalarını oluşturur.
* **Controller:** Gelen istekleri işler, modellerden veri alır ve uygun view dosyasına gönderir.

Bu projede MVC yapısı, çok uygulamalı mimariyle birlikte kullanılmıştır.

Bu nedenle projenin mimarisi şu şekilde ifade edilebilir:

```text
Laravel MVC + Multi-Application Architecture
```

Bu mimaride:

* `job-app`, iş arayan kullanıcı arayüzünü sağlar.
* `job-backoffice`, admin ve şirket sahibi yönetim panelini sağlar.
* `job-shared`, ortak Eloquent modellerini içerir.
* `MariaDB`, iki uygulama tarafından ortak kullanılan veritabanıdır.

---

## Kullanıcı Rolleri

Projede üç temel kullanıcı rolü bulunmaktadır:

```text
admin
company-owner
job-seeker
```

### Admin

Admin, sistemde en geniş yetkiye sahip kullanıcıdır.

Adminin yetkileri:

* Tüm kullanıcıları yönetmek.
* Şirketleri yönetmek.
* İş kategorilerini yönetmek.
* Tüm iş ilanlarını görüntülemek ve düzenlemek.
* Tüm başvuruları görüntülemek.
* Sistem genelindeki istatistikleri takip etmek.
* Arşivlenen kayıtları geri yüklemek.

### Company-owner

Company-owner, sistemde şirket sahibi rolüdür.

Company-owner’ın yetkileri:

* Kendi şirket bilgilerini görüntülemek ve düzenlemek.
* Kendi şirketine ait iş ilanları oluşturmak.
* Kendi ilanlarına gelen başvuruları görüntülemek.
* Başvuru durumlarını güncellemek.
* Kendi şirketine ait veriler üzerinde işlem yapmak.

Company-owner tüm sistemi değil, yalnızca kendi şirketine ait verileri yönetir.

### Job-seeker

Job-seeker, iş arayan kullanıcı rolüdür.

Job-seeker’ın yetkileri:

* İş ilanlarını listelemek.
* İş ilanı detaylarını görüntülemek.
* PDF formatında CV yüklemek.
* İş ilanlarına başvuru yapmak.
* Başvuru durumunu takip etmek.
* AI score ve AI feedback sonuçlarını görmek.

---

## Veritabanı Yapısı

Projede veritabanı olarak **MariaDB** kullanılmıştır.

Veritabanı adı:

```text
shaghal_db
```

Temel tablolar:

```text
users
companies
job_categories
job_vacancies
resumes
job_applications
sessions
password_reset_tokens
```

### users

Kullanıcı bilgilerini ve kullanıcı rollerini tutar.

Roller:

```text
admin
company-owner
job-seeker
```

### companies

Şirket bilgilerini tutar. Her şirket bir company-owner kullanıcısına bağlıdır.

İlişki:

```text
users.id → companies.ownerid
```

### job_categories

İş ilanı kategorilerini tutar.

Örnek kategoriler:

```text
Frontend Development
Backend Development
DevOps Engineering
Mobile Development
```

### job_vacancies

İş ilanlarını tutar. Her iş ilanı bir şirkete ve bir kategoriye bağlıdır.

İlişkiler:

```text
companies.id → job_vacancies.company_id
job_categories.id → job_vacancies.jobcategory_id
```

### resumes

Kullanıcıların yüklediği CV dosyalarını ve AI tarafından çıkarılan CV analiz sonuçlarını tutar.

Bu tabloda tutulabilecek bilgiler:

```text
filename
fileurl
summary
skills
experience
education
userid
```

### job_applications

İş başvurularını tutar.

Bu tabloda başvuru durumu ve AI değerlendirme sonuçları yer alır.

Önemli alanlar:

```text
status
aigeneratedscore
aigeneratedfeedback
userid
resumeid
jobvacancyid
```

---

## Eloquent ORM ve Model İlişkileri

Projede Laravel’in Eloquent ORM yapısı kullanılmıştır.

Eloquent ORM, veritabanı tablolarını PHP sınıfları olarak kullanmayı sağlar. Böylece SQL sorgularını doğrudan yazmadan modeller üzerinden veritabanı işlemleri yapılabilir.

Temel model ilişkileri:

```text
User → Resume
User → JobApplication
User → Company
Company → JobVacancy
JobVacancy → JobApplication
Resume → JobApplication
JobCategory → JobVacancy
```

Örnek ilişki açıklaması:

* Bir company-owner bir şirkete sahip olabilir.
* Bir şirketin birden fazla iş ilanı olabilir.
* Bir iş ilanına birden fazla başvuru yapılabilir.
* Bir job-seeker birden fazla CV yükleyebilir.
* Bir CV bir başvuruda kullanılabilir.

---

## CV Analizi ve Yapay Zekâ Entegrasyonu

Projede yapay zekâ bileşeni olarak **OpenAI API** kullanılmıştır.

Kullanıcı bir iş ilanına başvuru yaparken PDF formatında CV yükleyebilir veya daha önce yüklediği CV’yi seçebilir.

Başvuru süreci şu şekilde çalışır:

1. Kullanıcı iş ilanına başvuru yapmak ister.
2. Sistem kullanıcıdan CV dosyası ister.
3. CV dosyası Cloud Storage üzerinde `resumes/` klasörü altına kaydedilir.
4. PDF içindeki metin çıkarılır.
5. Çıkarılan CV metni OpenAI API’ye gönderilir.
6. OpenAI, CV içeriğini analiz eder.
7. Sistem CV’den özet, yetenekler, deneyim ve eğitim bilgilerini çıkarır.
8. CV bilgileri iş ilanı bilgileriyle karşılaştırılır.
9. AI score ve AI feedback üretilir.
10. Sonuçlar `job_applications` tablosuna kaydedilir.

OpenAI API iki temel amaçla kullanılmıştır:

```text
1. CV metnini yapılandırmak
2. CV ile iş ilanı arasında uygunluk değerlendirmesi yapmak
```

AI analiz sonucunda üretilen bilgiler:

```text
summary
skills
experience
education
aigeneratedscore
aigeneratedfeedback
```

### AI Score

AI score, adayın ilgili iş ilanına ne kadar uygun olduğunu gösteren puandır.

### AI Feedback

AI feedback, adayın neden uygun veya eksik görüldüğünü açıklayan metinsel değerlendirmedir.

---

## PDF Metin Çıkarma Süreci

Projede CV dosyasındaki metni çıkarmak için `pdftotext` yaklaşımı kullanılmıştır.

`pdftotext`, PDF dosyası içerisinde gerçek metin varsa bu metni çıkarır.

Bu projede OCR kullanılmamıştır.

OCR, taranmış veya görüntü formatındaki PDF dosyalarındaki yazıları tanımak için kullanılır. Bu nedenle tamamen taranmış görsel CV dosyaları için gelecekte OCR desteği eklenebilir.

---

## Harici API Kullanımı

Projede harici API entegrasyonu olarak **OpenAI API** kullanılmıştır.

OpenAI API, Laravel uygulaması ile dış bir yapay zekâ servisi arasında iletişim kurulmasını sağlar.

Bu entegrasyon sayesinde sistem:

* CV metnini analiz eder.
* Aday bilgilerini yapılandırır.
* Adayı iş ilanı ile karşılaştırır.
* AI score üretir.
* AI feedback üretir.

Bu yönüyle proje yalnızca klasik bir CRUD uygulaması değildir. Aynı zamanda harici API kullanan ve yapay zekâ destekli değerlendirme yapan bir yazılım sistemidir.

---

## Dosya Saklama Yapısı

CV dosyaları Cloud Storage üzerinde saklanır.

Dosyalar şu klasör altında tutulur:

```text
resumes/
```

Veritabanında ise dosya ile ilgili bilgiler ve analiz sonuçları tutulur.

Örnek bilgiler:

```text
filename
fileurl
summary
skills
experience
education
```

Bu yapı sayesinde dosyanın kendisi depolama servisinde, dosyaya ait bilgi ve analiz sonuçları ise veritabanında tutulur.

---

## Güvenlik ve Yetkilendirme

Projede kullanıcı giriş ve oturum işlemleri için Laravel Breeze kullanılmıştır.

Yetkilendirme tarafında `RoleMiddleware` kullanılmıştır.

RoleMiddleware, giriş yapan kullanıcının rolünün ilgili route için uygun olup olmadığını kontrol eder.

Örnek route yetkilendirmeleri:

```text
role:admin
role:company-owner
role:admin,company-owner
role:job-seeker
```

Eğer kullanıcının rolü uygun değilse sistem `403 Forbidden` hatası döndürür.

### Role Check ve Ownership Check

Projede iki önemli güvenlik kavramı bulunmaktadır:

```text
Role Check
Ownership Check
```

**Role Check**, kullanıcının rolünü kontrol eder.

Örneğin kullanıcının company-owner olup olmadığını kontrol eder.

**Ownership Check** ise erişilen verinin gerçekten o kullanıcıya ait olup olmadığını kontrol eder.

Örneğin company-owner sadece kendi şirketine ait iş ilanlarını ve başvuruları görmelidir.

Bu nedenle company-owner tarafında veri filtreleme ve sahiplik kontrolü güvenlik açısından önemlidir.

---

## Soft Delete / Archive ve Restore

Projede bazı kayıtlar doğrudan silinmez. Bunun yerine soft delete yapısı kullanılır.

Soft delete işleminde kayıt veritabanından tamamen kaldırılmaz. Kayıt üzerinde `deleted_at` alanı doldurulur.

Aktif kayıt:

```text
deleted_at = null
```

Arşivlenmiş kayıt:

```text
deleted_at = tarih bilgisi
```

Bu sayede yanlışlıkla silinen kayıtlar gerektiğinde restore işlemi ile geri alınabilir.

Soft delete yapısı şu modellerde kullanılır:

```text
User
Company
JobCategory
JobVacancy
Resume
JobApplication
```

---

## Kullanılan Teknolojiler

Projede kullanılan temel teknolojiler ve görevleri aşağıdaki gibidir:

| Teknoloji      | Kullanım Amacı                                                 |
| -------------- | -------------------------------------------------------------- |
| Laravel 12     | Backend, routing, controller, validation ve MVC yapısı         |
| Laravel Breeze | Login, register, session ve authentication işlemleri           |
| MariaDB        | İlişkisel veritabanı yönetimi                                  |
| Eloquent ORM   | Veritabanı tablolarını modellerle yönetme                      |
| Blade          | Kullanıcı arayüzlerini oluşturma                               |
| Tailwind CSS   | Modern ve responsive arayüz tasarımı                           |
| Vite           | Frontend asset derleme ve geliştirme sunucusu                  |
| Alpine.js      | Basit frontend etkileşimleri                                   |
| Composer       | PHP paket yönetimi ve job-shared path repository               |
| npm            | Frontend paket yönetimi                                        |
| Docker         | MariaDB ve phpMyAdmin servislerini container içinde çalıştırma |
| Docker Compose | Çoklu servislerin birlikte yönetilmesi                         |
| phpMyAdmin     | Veritabanını tarayıcı üzerinden görüntüleme ve yönetme         |
| OpenAI API     | CV analizi, AI score ve AI feedback üretimi                    |
| Cloud Storage  | PDF CV dosyalarının saklanması                                 |
| GitHub         | Versiyon kontrolü ve proje teslimi                             |

---

## Yerel Çalışma Ortamı

Projede yerel geliştirme ortamında Docker, XAMPP, Composer, npm ve phpMyAdmin kullanılmıştır.

Docker, MariaDB ve phpMyAdmin servislerini container içinde çalıştırmak için kullanılmıştır.

XAMPP, yerel ortamda PHP CLI sağlamak için kullanılmıştır.

MariaDB veritabanı Docker üzerinde çalıştırılmıştır. XAMPP içerisindeki MySQL servisi ile Docker MariaDB aynı portu kullanabileceği için port çakışmasına dikkat edilmelidir.

---

## Kurulum Gereksinimleri

Projeyi çalıştırmak için aşağıdaki araçlara ihtiyaç vardır:

```text
PHP 8.2+
Composer
Node.js
npm
Docker
Docker Compose
Git
```

---

## Veritabanını Çalıştırma

```bash
cd docker/phpMyAdmin_mariadb
docker-compose up -d
```

phpMyAdmin erişim adresi:

```text
http://localhost:8081
```

MariaDB erişim bilgileri:

```text
Host: 127.0.0.1
Port: 3306
Database: shaghal_db
```

---

## job-backoffice Çalıştırma

```bash
cd job-backoffice
composer install
npm install
php artisan migrate
php artisan db:seed
php artisan serve --port=8001
```

Ayrı bir terminalde:

```bash
cd job-backoffice
npm run dev
```

Erişim adresi:

```text
http://localhost:8001
```

---

## job-app Çalıştırma

```bash
cd job-app
composer install
npm install
php artisan serve --port=8000
```

Ayrı bir terminalde:

```bash
cd job-app
npm run dev
```

Erişim adresi:

```text
http://localhost:8000
```

---

## Uygulama Erişim Adresleri

```text
job-app:        http://localhost:8000
job-backoffice: http://localhost:8001
phpMyAdmin:     http://localhost:8081
MariaDB:        localhost:3306
```

---

## Demo Akışı

Proje demo sırasında aşağıdaki sırayla gösterilebilir:

1. Docker servislerinin çalıştığı gösterilir.
2. phpMyAdmin üzerinden `shaghal_db` veritabanı gösterilir.
3. `job-backoffice` üzerinden admin girişi yapılır.
4. Admin dashboard ve yönetim modülleri gösterilir.
5. Company-owner hesabı ile giriş yapılır.
6. Company-owner’ın kendi şirketi, ilanları ve başvuruları gösterilir.
7. `job-app` üzerinden job-seeker girişi yapılır.
8. İş ilanları listelenir.
9. Bir iş ilanı detay sayfası açılır.
10. CV ile başvuru yapılır.
11. AI score ve AI feedback sonucu gösterilir.
12. Başvuru kaydının veritabanında tutulduğu gösterilir.
13. GitHub repository ve teknik doküman açıklanır.

---

## GitHub Repository

Proje GitHub üzerinde versiyon kontrolüne alınmıştır.

Repository bağlantısı:

```text
https://github.com/Hamzaaladaadgithab/shaghalni-job-portal
```

Projede kullanılan ana branch:

```text
main
```

---

## Proje Dizin Yapısı

```text
shaghalni-job-portal/
├── docker/
│   └── phpMyAdmin_mariadb/
├── job-app/
│   ├── app/
│   ├── routes/
│   ├── resources/
│   ├── database/
│   └── composer.json
├── job-backoffice/
│   ├── app/
│   ├── routes/
│   ├── resources/
│   ├── database/
│   └── composer.json
├── job-shared/
│   ├── src/
│   │   └── Models/
│   └── composer.json
└── README.md
```

---

## Değerlendirme Ölçütlerine Göre Proje Durumu

| Kriter              | Projedeki Karşılığı                                                 |
| ------------------- | ------------------------------------------------------------------- |
| Problem Tanımı      | İş arayanlar ve şirketler için dijital iş ilanı ve başvuru yönetimi |
| Sistem Tasarımı     | job-app, job-backoffice, job-shared ve ortak veritabanı yapısı      |
| Yazılım Mimarisi    | Laravel MVC ve çok uygulamalı mimari                                |
| Yapay Zekâ Bileşeni | OpenAI destekli CV analizi, AI score ve AI feedback                 |
| Arayüz Kalitesi     | job-app kullanıcı arayüzü ve job-backoffice yönetim paneli          |
| API Kullanımı       | OpenAI API entegrasyonu                                             |
| Kod Kalitesi        | Controller, Request, Service, Middleware ve Shared Model yapısı     |
| Rapor               | Teknik doküman PDF olarak hazırlanmıştır                            |
| Sunum               | Canlı demo ve teknik anlatım ile desteklenmiştir                    |

---

## Teknik Soru ve Cevaplar

### Neden iki ayrı Laravel uygulaması kullanıldı?

İş arayan kullanıcı arayüzü ile yönetim panelini birbirinden ayırmak için iki ayrı Laravel uygulaması kullanılmıştır. `job-app`, iş ilanlarını görüntüleme, CV yükleme ve başvuru yapma süreçleri için; `job-backoffice` ise admin ve company-owner yönetim işlemleri için geliştirilmiştir.

### job-shared neden kullanıldı?

`User`, `Company`, `JobVacancy`, `Resume` ve `JobApplication` gibi Eloquent modellerinin iki Laravel uygulaması tarafından ortak kullanılabilmesi için `job-shared` yapısı oluşturulmuştur. Böylece tekrar kod yazımı azaltılmış ve veri modeli tutarlılığı sağlanmıştır.

### Admin ve company-owner arasındaki fark nedir?

Admin sistemin tamamını yönetir. Company-owner ise yalnızca kendi şirketini, kendi iş ilanlarını ve bu ilanlara gelen başvuruları yönetebilir.

### RoleMiddleware ne yapar?

RoleMiddleware, giriş yapan kullanıcının `role` bilgisinin ilgili route için uygun olup olmadığını kontrol eder. Kullanıcının rolü yetkili değilse sistem `403 Forbidden` hatası döndürür.

### Ownership filtering neden önemlidir?

Company-owner rolüne sahip olmak tek başına yeterli değildir. Erişilen kaydın gerçekten o company-owner’ın kendi şirketine ait olup olmadığı da kontrol edilmelidir. Bu kontrol veri güvenliği açısından önemlidir.

### Docker projede ne için kullanıldı?

Docker, MariaDB ve phpMyAdmin servislerini izole container yapıları içinde çalıştırmak için kullanılmıştır. Böylece veritabanı ortamı daha düzenli, taşınabilir ve yönetilebilir hale gelmiştir.

### OpenAI neden kullanıldı?

OpenAI, CV metnini analiz etmek, aday bilgilerini yapılandırmak ve CV ile iş ilanını karşılaştırarak `AI score` ve `AI feedback` üretmek için kullanılmıştır.

### pdftotext ve OCR arasındaki fark nedir?

`pdftotext`, PDF içindeki gerçek metni çıkarır. OCR ise görüntü veya taranmış PDF içindeki yazıları tanımaya çalışır. Bu projede OCR kullanılmamıştır.

### Soft delete nedir?

Soft delete işleminde kayıt veritabanından tamamen silinmez. Bunun yerine `deleted_at` alanı doldurularak kayıt arşivlenir. Gerektiğinde `restore` işlemiyle tekrar aktif hale getirilebilir.

---

## Geliştirilebilir Yönler

Proje mevcut haliyle temel gereksinimleri karşılamaktadır. Ancak ileride aşağıdaki geliştirmeler yapılabilir:

* Taranmış PDF dosyaları için OCR desteği eklenebilir.
* Company-owner için policy tabanlı ownership kontrolü güçlendirilebilir.
* Başvuru durum değişiklikleri için bildirim sistemi eklenebilir.
* REST API endpointleri eklenebilir.
* Mobil uygulama desteği geliştirilebilir.
* Otomatik test kapsamı artırılabilir.
* AI hata durumları için daha gelişmiş kullanıcı bilgilendirmeleri yapılabilir.

---

## Sonuç

AI Destekli İş İlanı Platformu, Laravel 12 ile geliştirilmiş, çok uygulamalı, rol tabanlı ve yapay zekâ destekli bir iş ilanı sistemidir.

Proje; iş arayan kullanıcıların iş ilanlarını görüntülemesini, CV yükleyerek başvuru yapmasını, şirket sahiplerinin kendi ilanlarını ve başvurularını yönetmesini, admin kullanıcısının ise tüm sistemi kontrol etmesini sağlar.

OpenAI API entegrasyonu sayesinde sistem, CV metnini analiz edebilir, aday bilgilerini yapılandırabilir ve iş ilanına uygunluk değerlendirmesi yapabilir.

Bu yönleriyle proje; modern yazılım mimarisi, veritabanı yönetimi, kullanıcı arayüzü, harici API entegrasyonu ve yapay zekâ bileşenlerini bir araya getiren kapsamlı bir bitirme projesidir.
