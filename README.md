# 🚀 نظام بوابة الوظائف

منصة شاملة للوظائف مبنية بـ Laravel 12، تتكون من تطبيقين منفصلين للباحثين عن العمل والإدارة.

## 📋 نظرة عامة على المشروع

يتكون هذا المشروع من تطبيقي Laravel يعملان معاً:

- **job-app**: تطبيق الواجهة الأمامية للباحثين عن العمل
- **job-backoffice**: لوحة الإدارة لإدارة الشركات وإعلانات الوظائف والطلبات

## 🏗️ هيكل المشروع

```
workspace/
├── docker/                 # البنية التحتية لقاعدة البيانات
│   └── phpMyAdmin_mariadb/ # إعداد MariaDB + phpMyAdmin
├── job-app/               # الواجهة الأمامية للباحثين عن العمل
│   └── تطبيق Laravel 12   # تسجيل المستخدمين، تصفح الوظائف، التقديمات
└── job-backoffice/        # لوحة الإدارة
    └── تطبيق Laravel 12   # إدارة الشركات، نشر الوظائف، مراجعة الطلبات
```

## 🎯 الغرض من كل تطبيق

### 📱 job-app (الواجهة الأمامية للباحثين عن العمل)
**المستخدمون المستهدفون:** الباحثون عن العمل، المرشحون
**الحالة الحالية:** 🟡 مكتمل بنسبة 20%

**الميزات:**
- ✅ تسجيل المستخدمين والمصادقة
- ✅ إدارة الملف الشخصي
- ❌ عرض قوائم الوظائف (مطلوب)
- ❌ نظام التقديم للوظائف (مطلوب)
- ❌ رفع/إدارة السيرة الذاتية (مطلوب)
- ❌ تتبع حالة الطلبات (مطلوب)

**الوصول:** http://localhost:8000

### 🏢 job-backoffice (لوحة الإدارة)
**المستخدمون المستهدفون:** مديرو النظام، أصحاب الشركات، موظفو الموارد البشرية
**الحالة الحالية:** 🟢 مكتمل بنسبة 80%

**الميزات:**
- ✅ إدارة الشركات (إنشاء، قراءة، تحديث، حذف)
- ✅ إدارة إعلانات الوظائف (إنشاء، قراءة، تحديث، حذف)
- ✅ مراجعة طلبات التوظيف (إنشاء، قراءة، تحديث، حذف)
- ✅ إدارة فئات الوظائف (إنشاء، قراءة، تحديث، حذف)
- ✅ إدارة المستخدمين (جزئي)
- ✅ نظام التقييم بالذكاء الاصطناعي (الخلفية جاهزة)
- ✅ وظيفة الحذف الناعم والاستعادة
- ✅ لوحة تحكم مع التحليلات

**الوصول:** http://localhost:8001

## 🗄️ مخطط قاعدة البيانات

**قاعدة البيانات المشتركة:** `shaghal_db` (كلا التطبيقين يستخدمان نفس قاعدة البيانات)

### الجداول:
- `users` - مستخدمو النظام مع الأدوار (admin, company-owner, job-seeker)
- `companies` - ملفات الشركات
- `job_categories` - تصنيف الوظائف
- `job_vacancies` - إعلانات الوظائف
- `resumes` - السير الذاتية للمستخدمين مع البيانات المحللة
- `job_applications` - الطلبات مع تقييم الذكاء الاصطناعي
- `sessions` - جلسات المستخدمين
- `password_reset_tokens` - وظيفة إعادة تعيين كلمة المرور

### الميزات الرئيسية:
- مفاتيح أساسية UUID
- الحذف الناعم مفعل
- قيود المفاتيح الخارجية
- حقول تقييم الذكاء الاصطناعي (aigeneratedscore, aigeneratedfeedback)

## 🚀 البدء السريع

### المتطلبات الأساسية
- PHP 8.2+
- Composer
- Node.js & npm
- Docker & Docker Compose

### 1. تشغيل قاعدة البيانات
```bash
cd docker/phpMyAdmin_mariadb
docker-compose up -d
```

### 2. إعداد job-backoffice (لوحة الإدارة)
```bash
cd job-backoffice

# تثبيت التبعيات
composer install
npm install

# إعداد قاعدة البيانات
php artisan migrate
php artisan db:seed

# تشغيل الخادم
php artisan serve --port=8001

# تشغيل Vite (terminal منفصل)
npm run dev
```

### 3. إعداد job-app (الواجهة الأمامية)
```bash
cd job-app

# تثبيت التبعيات
composer install
npm install

# تشغيل الخادم
php artisan serve --port=8000

# تشغيل Vite (terminal منفصل)
npm run dev
```

### 4. الوصول للتطبيقات
- **بوابة الوظائف (الواجهة الأمامية):** http://localhost:8000
- **لوحة الإدارة:** http://localhost:8001
- **phpMyAdmin:** http://localhost:8081
- **قاعدة البيانات:** localhost:3306

## 👥 أدوار المستخدمين والوصول

### مستخدم الإدارة
- **البريد الإلكتروني:** admin@admin.com
- **كلمة المرور:** 12345678
- **الوصول:** التحكم الكامل في النظام عبر job-backoffice

### صاحب الشركة
- يتم إنشاؤه بواسطة المدير من خلال job-backoffice
- **الوصول:** إدارة الشركة والوظائف عبر job-backoffice

### الباحث عن العمل
- التسجيل الذاتي عبر job-app
- **الوصول:** تصفح الوظائف والتقديمات عبر job-app

## 🛠️ مجموعة التقنيات

### الخلفية (Backend)
- **الإطار:** Laravel 12
- **قاعدة البيانات:** MariaDB
- **المصادقة:** Laravel Breeze
- **الاختبار:** Pest PHP
- **جودة الكود:** Laravel Pint

### الواجهة الأمامية (Frontend)
- **أداة البناء:** Vite 7.0.7
- **إطار CSS:** Tailwind CSS 3.1.0
- **JavaScript:** Alpine.js 3.4.2
- **عميل HTTP:** Axios 1.11.0

### أدوات التطوير
- **الحاويات:** Docker & Docker Compose
- **واجهة قاعدة البيانات:** phpMyAdmin
- **إدارة الحزم:** Composer (PHP), npm (Node.js)

## 📁 هيكل المشروع

### هيكل job-app
```
job-app/
├── app/
│   ├── Http/Controllers/
│   │   └── ProfileController.php
│   └── Models/
│       └── User.php (أساسي)
├── routes/
│   ├── web.php (routes أساسية)
│   └── auth.php
├── resources/views/
│   ├── dashboard.blade.php
│   ├── auth/ (تسجيل الدخول، التسجيل)
│   └── profile/
└── database/migrations/ (Laravel أساسي)
```

### هيكل job-backoffice
```
job-backoffice/
├── app/
│   ├── Http/Controllers/
│   │   ├── CompanyController.php
│   │   ├── JobVacancyController.php
│   │   ├── JobApplicationController.php
│   │   ├── JobCategoryController.php
│   │   ├── UserController.php
│   │   └── DashboardController.php
│   ├── Models/
│   │   ├── User.php (مع الأدوار)
│   │   ├── Company.php
│   │   ├── JobVacancy.php
│   │   ├── JobApplication.php
│   │   ├── JobCategory.php
│   │   └── Resume.php
│   └── Http/Requests/ (التحقق من النماذج)
├── routes/web.php (routes CRUD كاملة)
├── resources/views/
│   ├── company/
│   ├── job-vacancy/
│   ├── job-application/
│   ├── job-category/
│   ├── user/
│   └── dashboard/
└── database/
    ├── migrations/ (مخطط كامل)
    ├── seeders/
    └── data/ (ملفات JSON للبذر)
```

## 🔄 سير العمل التطويري

### التطوير اليومي
```bash
# تشغيل قاعدة البيانات
docker-compose up -d

# Terminal 1: job-backoffice
cd job-backoffice
php artisan serve --port=8001

# Terminal 2: job-backoffice Vite
cd job-backoffice
npm run dev

# Terminal 3: job-app
cd job-app
php artisan serve --port=8000

# Terminal 4: job-app Vite
cd job-app
npm run dev
```

### عمليات قاعدة البيانات
```bash
# migration جديد مع seed
php artisan migrate:fresh --seed

# تشغيل seeder محدد
php artisan db:seed --class=DatabaseSeeder

# إنشاء migration جديد
php artisan make:migration create_table_name

# إنشاء model جديد مع migration
php artisan make:model ModelName -m
```

## 📋 قائمة المهام المطلوبة

### أولوية عالية (job-app)
- [ ] صفحة عرض قوائم الوظائف
- [ ] البحث والتصفية في الوظائف
- [ ] نموذج التقديم للوظائف
- [ ] نظام رفع السيرة الذاتية
- [ ] تتبع حالة الطلبات
- [ ] تحسينات لوحة تحكم المستخدم

### أولوية متوسطة (job-backoffice)
- [ ] إكمال تنفيذ UserController
- [ ] تحليلات ومخططات لوحة التحكم
- [ ] واجهة إدارة السيرة الذاتية
- [ ] نظام الإشعارات عبر البريد الإلكتروني
- [ ] خيارات التصفية المتقدمة

### أولوية منخفضة (كلاهما)
- [ ] نقاط نهاية API لتطبيق الهاتف المحمول
- [ ] البحث المتقدم مع Elasticsearch
- [ ] الإشعارات في الوقت الفعلي
- [ ] تحسين تخزين الملفات
- [ ] تحسين الأداء
- [ ] اختبار شامل

## 🐛 المشاكل المعروفة

1. **job-app**: معظم العروض فارغة/أساسية
2. **job-backoffice**: قد تكون بعض العروض مفقودة
3. **نظام السيرة الذاتية**: الخلفية جاهزة لكن لا توجد واجهة رفع الملفات
4. **إشعارات البريد الإلكتروني**: مكونة لكن غير منفذة
5. **الاختبار**: ملفات الاختبار موجودة لكنها فارغة

## 🔧 التكوين

### متغيرات البيئة
كلا التطبيقين يستخدمان تكوين `.env` مشابه:

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shaghal_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

### تكوين Docker
- **MariaDB**: المنفذ 3306، بدون كلمة مرور (للتطوير)
- **phpMyAdmin**: المنفذ 8081، حد رفع 2GB

## 📊 التقدم الحالي

| المكون | الحالة | نسبة الإكمال |
|--------|--------|-------------|
| مخطط قاعدة البيانات | ✅ مكتمل | 100% |
| job-backoffice الخلفية | ✅ مكتمل في الغالب | 80% |
| job-backoffice الواجهة الأمامية | 🟡 جزئي | 60% |
| job-app الخلفية | 🟡 أساسي | 30% |
| job-app الواجهة الأمامية | ❌ الحد الأدنى | 20% |
| المصادقة | ✅ مكتمل | 100% |
| إعداد Docker | ✅ مكتمل | 100% |

## 🤝 المساهمة

1. احتفظ بالهيكل الحالي (لا تعيد الهيكلة حتى الاكتمال)
2. ركز على إكمال job-app أولاً
3. وثق أي ميزات جديدة
4. اختبر بدقة قبل الالتزام
5. اتبع معايير ترميز Laravel

## 📞 الدعم

للأسئلة حول هذا المشروع:
1. تحقق من هذا README أولاً
2. راجع تعليقات الكود
3. تحقق من قائمة المهام
4. انظر إلى مخطط قاعدة البيانات

## 🔄 تاريخ الإصدارات

- **v1.0** - الإعداد الأولي مع تطبيقي Laravel مزدوجين
- **الحالي** - عمليات CRUD الأساسية، المصادقة، مخطط قاعدة البيانات

---

**آخر تحديث:** ديسمبر 2024
**حالة المشروع:** قيد التطوير (مكتمل بنسبة 55-60%)