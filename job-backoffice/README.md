# Job Backoffice - İş İlanları Yönetim Sistemi

Bu proje, şirketlerin iş ilanlarını yönetebileceği ve iş başvurularını takip edebileceği kapsamlı bir Laravel uygulamasıdır. Modern web teknolojileri kullanılarak geliştirilmiş, güvenli ve performanslı bir sistem sunmaktadır.

## 🚀 Özellikler

### 📋 Form Validasyon ve Güvenlik
- **Kapsamlı Form Validation**: CompanyCreateRequest ve CompanyUpdateRequest sınıfları ile detaylı doğrulama
- **CSRF Koruması**: Tüm formlarda Laravel CSRF token güvenliği
- **Email Format Kontrolü**: Geçerli email adresi doğrulaması
- **Unique Kontroller**: Şirket adı ve email adreslerinde tekrarlama kontrolü
- **Şifre Güvenliği**: Minimum 8 karakter şifre kuralı
- **Türkçe Hata Mesajları**: Kullanıcı dostu hata bildirimleri

```php
// Örnek Validation Kuralları
'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
'owner_email' => ['required', 'email','unique:users,email', 'max:255'],
'owner_password' => ['required', 'string', 'min:8'],
```

### 🎨 Modern Kullanıcı Arayüzü
- **TailwindCSS**: Utility-first CSS framework ile hızlı styling
- **Alpine.js**: Hafif JavaScript framework ile interaktif özellikler
- **Responsive Tasarım**: Mobil ve desktop uyumlu arayüz
- **Şifre Göster/Gizle**: Alpine.js ile dinamik şifre görünürlüğü
- **Toast Notifications**: Kullanıcı işlem bildirimleri
- **Overflow Scroll**: Mobil cihazlarda tablo uyumluluğu

```blade
<!-- Alpine.js Şifre Toggle Örneği -->
<div class="relative" x-data="{ showPassword: false }">
    <input x-bind:type="showPassword ? 'text':'password'"/>
    <button @click="showPassword = !showPassword">Toggle</button>
</div>
```

### ⚡ Performans Optimizasyonu
- **Eager Loading**: N+1 sorgu probleminin çözümü
- **Pagination**: Sayfa başına 10 kayıt ile performans artışı
- **WithCount**: Veritabanı seviyesinde sayım işlemleri
- **Query Optimization**: Dashboard'da karmaşık sorgu optimizasyonları
- **Conversion Rate**: Tek sorguda dönüşüm oranı hesaplama

```php
// Eager Loading Örneği
$company = Company::with('owner')->findOrFail($id);

// WithCount Optimizasyonu
$mostAppliedJobs = JobVacancy::withCount('jobapplications')
    ->with('company')
    ->orderBy('jobapplications_count', 'desc')
    ->take(5)
    ->get();
```

### 🛡️ Hata Yönetimi ve Güvenlik
- **Authorization Control**: Kullanıcı yetki kontrolü
- **404 Auto Handle**: findOrFail() ile otomatik 404 yönetimi
- **Null Checks**: Blade template'lerde güvenli veri gösterimi
- **Error Messages**: Kullanıcı dostu hata bildirimleri
- **Redirect with Flash**: Session ile geçici mesajlar

```php
// Hata Yönetimi Örneği
if (!Auth::check()) {
    abort(401, 'Unauthorized');
}

if (!$company) {
    return redirect()->route('dashboard')
        ->with('error', 'No company found for your account.');
}
```

### 🗑️ Soft Delete ve Veri Güvenliği
- **SoftDeletes Trait**: Tüm modellerde güvenli silme
- **Archive/Restore**: Silinen kayıtları geri getirme
- **Data Recovery**: Yanlışlıkla silinen verilerin korunması
- **Conditional Views**: Arşivlenmiş kayıtlar için özel görünüm
- **Timestamp Management**: deleted_at alanı ile tarih takibi

```php
// Soft Delete Kullanımı
use Illuminate\Database\Eloquent\SoftDeletes;

class JobVacancy extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
}

// Controller'da kullanım
$query->onlyTrashed(); // Sadece silinmiş kayıtlar
$jobvacancy->restore(); // Geri yükleme
```

## 🏗️ Teknik Yapı

### Backend
- **Laravel Framework**: PHP web application framework
- **MySQL Database**: İlişkisel veritabanı yönetimi
- **Eloquent ORM**: Veritabanı işlemleri için ORM
- **Form Requests**: Validation ve authorization
- **Middleware**: Authentication ve authorization

### Frontend
- **Blade Templates**: Laravel template engine
- **TailwindCSS**: Utility-first CSS framework
- **Alpine.js**: Minimal JavaScript framework
- **Responsive Design**: Mobile-first approach

### Güvenlik
- **CSRF Protection**: Cross-site request forgery koruması
- **Authentication**: Laravel built-in auth system
- **Authorization**: Role-based access control
- **Input Validation**: Server-side validation
- **SQL Injection Protection**: Eloquent ORM koruması

## 📊 Veritabanı Yapısı

### Ana Tablolar
- **users**: Kullanıcı bilgileri (admin, company-owner)
- **companies**: Şirket bilgileri
- **job_vacancies**: İş ilanları
- **job_applications**: İş başvuruları
- **job_categories**: İş kategorileri

### İlişkiler
- User → Company (One to One)
- Company → JobVacancy (One to Many)
- JobVacancy → JobApplication (One to Many)
- User → JobApplication (One to Many)

## 🎯 Kullanıcı Rolleri

### Admin
- Tüm şirketleri görüntüleme ve yönetme
- Tüm iş ilanlarını görüntüleme
- Tüm başvuruları görüntüleme
- Sistem geneli raporlar

### Company Owner
- Kendi şirketini yönetme
- Şirketine ait iş ilanlarını yönetme
- Şirketine gelen başvuruları değerlendirme
- Şirket bazlı raporlar

## 🚀 Kurulum

1. **Repository'yi klonlayın**
```bash
git clone [repository-url]
cd job-backoffice
```

2. **Bağımlılıkları yükleyin**
```bash
composer install
npm install
```

3. **Environment dosyasını yapılandırın**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Veritabanını oluşturun**
```bash
php artisan migrate
php artisan db:seed
```

5. **Asset'leri derleyin**
```bash
npm run dev
```

6. **Sunucuyu başlatın**
```bash
php artisan serve
```

## 📈 Dashboard Özellikleri

- **İstatistikler**: Toplam şirket, ilan ve başvuru sayıları
- **En Çok Başvuru Alan İşler**: withCount() ile optimize edilmiş
- **Dönüşüm Oranları**: Kabul edilen başvuru yüzdeleri
- **Grafik Gösterimler**: Görsel veri analizi
- **Real-time Updates**: Güncel veriler

## 🔧 Geliştirme Notları

### Performans İyileştirmeleri
- Eager Loading ile N+1 problem çözümü
- Database indexing
- Query optimization
- Pagination implementation

### Güvenlik Önlemleri
- Input sanitization
- CSRF token validation
- SQL injection prevention
- XSS protection

### Code Quality
- PSR-4 autoloading
- SOLID principles
- Clean code practices
- Comprehensive error handling

## 📝 Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## 👨‍💻 Geliştirici

Bu proje, modern Laravel development practices kullanılarak geliştirilmiştir. Tüm özellikler production-ready olarak tasarlanmış ve test edilmiştir.
