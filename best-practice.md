
# Best Practice - Pembuatan WordPress Plugin

Berikut beberapa best practive yang bisa Anda gunakan dalam membangun sebuah WordPress Plugin.

## 1. Pilih nama Plugin yang unik dan Mudah diingat

Ini hanya jika Anda ingin merilis plugin WordPress yang Anda buat ke publik.

Tujuanya adalah agar orang mudah mengingat dan memahami secara langsung ketika menyebut namanya.

## 2. Gunakan nama folder file yang unik

Agar nanti saat di install tidak bentrok dengan plugin lainya. Misalnya jangan gunakan nama yang sama dengan `seo` karena plugin dengan url tersebut sudah ada di direktori WordPress.

  

Untuk mengeceknya, Anda bisa buka link ini

```text
https://wordpress.org/plugins/seo/[folder-plugin-mu]
```


ganti `[folder-plugin-mu]` dengan nama folder plugin yang ingin kamu buat.

  

Jika hasilnya adalah halaman pencarian dari wordpress plugin directory. maka folder tersebut bisa kamu gunakan.

  

**Tips**
Gunakan prefix atau sufix untuk nama folder. Contoh

- `dankedev-seo`
- `seo-by-dankedev`
- dan lain-lain

## 3. Pahami dasar-dasar penulisan nama variable, function pada PHP

Anda harus dan wajib paham ini, meskipun mudah tapi terkadang bagi pemula sering terlewatkan. Misalnya yang wajib Anda lakukan terkait variable dan nama function
 - Tidak boleh ada spasi dan simbol kecuali under dash (_), dan juga tidak boleh diawali angka 
    - `❌ $setting-umum`
    - `❌ function register$include-script(){}`
    - `✅ $setting_umum`
    - `✅ function register_include_script(){}`

 - Ketika anda membuka, pastikan Anda menutupnya, misal
    - ❌  `$variable = 'seo'` // kurang titik koma `;`
    - ❌  `$variable = 'seo ;` // kurang penutup string `'`
    - ❌  `$variable = array(...` // kurang tutup kurung `)`
    - dll

Demikian juga dengan standard standard penulisan php lainy.

## 4. Hindari penamaan yang sama

Ketika plugin Anda aktif, secara otomatis semua kode yang Anda buat tadi akan di proses oleh WordPress bersamaan dengan dengan kode dari Core WordPress, Theme, atau plugin lain nya yang terinstall dalam web tersebut.

### Solusi dan Tips

#### Gunakan prefix

Tambahkan awalan (`prefix`) disetiap function khususnya, dan juga global variable, agar tidak terjadi bentrok.

contoh

```php
/**
Tambahkan dankedev_ sebagai prefix function (bebas sesuai kehendak Anda)
**/
dankedev_get_seo_title(){
    //kode lainya disini
}
```

#### Lakukan pengecekan diawal

PHP menyediakan banyak metode pengecekan, baik untuk varibale, nama function atau class atau konstanta. Misalnya
- Untuk mengecek variables, array key, atau object gunakan [isset()](http://php.net/manual/en/function.isset.php)
- Untuk mengecek nama function gunakan [function_exists()](http://php.net/manual/en/function.function-exists.php)
- untuk mengecek nama class gunakan [class_exists()](http://php.net/manual/en/function.class-exists.php)
- Untuk mengecek konstanta gunakan [defined()](http://php.net/manual/en/function.defined.php)

**Contoh**

```php
// cek keberadaan apakah sebuah key didefinisikan di dalam array

$args = isset($options['some_key']) ? $options['some_key']:'';

// cek apakah functions sudah ada atau belum

if(!function_exists('nama_function_yang_akan_dibuat')){
    function nama_function_yang_akan_dibuat(){
        // kode lainya disini
    }
}

// Cek apakah nama class sudah ada atau belum
if ( ! class_exists( 'NamaClass' ) ) {
    class NamaClass{
        // kode lainya disini
    }
}

```

### Gunakan Metode Object Oriented Programming dan NamaSpacing

Salah satu yang mudah diterapkan adalah menggunakan `class`. Dan gunakan `namespace` untuk membuatnya lebih unik.

Contoh

```php
namespace Dankedev;

class Plugin{
    protected static $variable;

    public static function init(){
        return self::$variable;
    }
}


//Bisa digunakan dengan cara
$init = \Dankedev\Plugin::init();
```


## 5. Management File yang Mudah

Jika plugin yang Anda buat nantinya memerlukan banyak file, misal file css, html, javascript, pihak ketika atau lainya. Maka menyusun struktur yang baik sangat-sangat diperlukan.

Gunanya adalah agar kita nanti tidak bingung saat proses development. 

Pisahkan file-file sejenis baik secara kegunaan ataupun berdasarkan ekstensi.

Berikut contoh struktur file yang kami sarankan

```
/nama-plugin
    nama-plugin.php
    uninstall.php
    /assets
        /admin
            /css
                /dashboard.css
                /...css
            /js
                /dashboard.js
                /chart.js
                /...js
        /public
            /css
                /my-app.css
                /...css
            /js
                /my-app.js
                /...js
            /images
                /logo.png
                /...jpg
    /includes/
        helper.php
        format.php
    /languages
    /vendor
```

Yang demikian hanya contoh saja, prakteknya terserah Anda yang tentunya seuai yang mudah bagi Anda.

## 6. Gunakan Conditional

Ini juga akan sangat membantu ketika Anda memiliki file yang banyak, namun penggunaanya berbeda-beda. Contoh ada function tertentu yang hanya akan digunakan di admin dan ada yang untuk user umum. Maka bisa gunakan metode ini:

```php
if(is_admin()){
        require_once __DIR__ . '/admin/plugin-name-admin.php';
}

//hanya untuk user yang login
if(is_user_logged_in()){
            require_once __DIR__ . '/user/profile-function.php';

}

require_once __DIR__ . '/admin/function-umum.php';
```


## 7. Sanitize First, Escape letter

Lakukan sanitize setiap kali anda melakukan post atau request
Contoh

```php
$id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : null;
```

### Prinsip Keamanan yang harus dijaga

1. Asas tidak percaya terhadap apapun yang menjadi input dari pengguna
2. Lakukan `escape` selama memungkinkan
3. Lakukan `escape` apapun yang berasal dari atau menuju ke "untrusted sources" misalnya database, user form, Rest API dll. 
4. Jangan berasumsi apapun
6. Lakukan "reject" jika mencurigakan.

Beberapa function `escape` yang bisa digunakan

- [esc_attr()](https://developer.wordpress.org/reference/functions/esc_attr/) // untuk string
- [esc_textarea()](https://developer.wordpress.org/reference/functions/esc_textarea/) // untuk string
- [esc_url()](https://developer.wordpress.org/reference/functions/esc_url/) // untuk string
- [esc_html()](https://developer.wordpress.org/reference/functions/esc_html/) // untuk string
- dll




