
# Best Practice - Pembuatan WordPress Plugin

Berikut beberapa best practive yang bisa Anda gunakan dalam membangun sebuah WordPress Plugin.

## 1. Pilih nama Plugin yang unik dan Mudah diingat

Ini hanya jika Anda ingin merilis plugin WordPress yang Anda buat ke publik.

Tujuanya adalah agar orang mudah mengingat dan memahami secara langsung ketika menyebut namanya.

## 2. Gunakan nama folder file yang unik

Agar nanti saat di install tidak bentrok dengan plugin lainya. Misalnya jangan gunakan nama yang sama dengan `seo` karena plugin dengan url tersebut sudah ada di direktori WordPress.

  

Untuk mengeceknya, Anda bisa buka link ini

```

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

```
/**
Tambahkan dankedev_ sebagai prefix function (bebas sesuai kehendak Anda)
**/
dankedev_get_seo_title(){
    //kode lainya disini
}
```