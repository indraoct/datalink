<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "Harus diterima.",
	"active_url"           => "URL tidak valid.",
	"after"                => "Tanggal harus melebihi :date.",
	"alpha"                => "Harap masukkan alphabet.",
	"alpha_dash"           => "Harap masukkan huruf, angka, atau dash saja.",
	"alpha_num"            => "Harap masukkan huruf atau angka saja.",
	"array"                => "Harus berupa array.",
	"before"               => "Tanggal harus sebelum :date.",
	"between"              => array(
		"numeric" => "Harap masukkan angka antara :min hingga :max.",
		"file"    => "Harap upload file antara :min hingga :max kilobytes.",
		"string"  => "Harap masukkan antara :min hingga :max karakter.",
		"array"   => "Harap masukkan jumlah antara :min hingga :max.",
	),
	"confirmed"            => "Tidak sesuai dengan konfirmasi.",
	"date"                 => "Tanggal tidak valid.",
	"date_format"          => "Format tanggal tidak sesuai (:format).",
	"different"            => ":attribute dan :other harus berbeda.",
	"digits"               => "Harap masukkan :digits digit angka.",
	"digits_between"       => "Harap masukkan digit angka antara :min hingga :max.",
	"email"                => "Alamat email tidak valid.",
	"exists"               => "Nilai yang dipilih tidak valid.",
	"greater_than"     => ":attribute harus lebih besar daripada :other.",
	"image"                => "FIle harus berupa gambar.",
	"in"                   => "Nilai yang dipilih tidak valid.",
	"integer"              => "Harus berupa integer.",
	"ip"                   => "IP address tidak valid.",
	"less_than"     => ":attribute harus lebih kecil daripada :other.",
	"max"                  => array(
		"numeric" => "Maksimal harus :max.",
		"file"    => "Harap upload file maksimal :max kilobytes.",
		"string"  => "Harap masukkan maksimal :max karakter.",
		"array"   => "Jumlah harus maksimal :max.",
	),
	"mimes"                => "Tipe file harus: :values.",
	"min"                  => array(
		"numeric" => "Minimal harus :min.",
		"file"    => "Harap upload file minimal :min kilobytes.",
		"string"  => "Harap masukkan minimal :min karakter.",
		"array"   => "Jumlah harus minimal :min.",
	),
	"not_in"               => "Nilai yang dipilih tidak valid.",
	"numeric"              => "Harap masukkan angka.",
	"regex"                => "Format tidak valid.",
	"required"             => "Harus diisi.",
	"required_if"          => "Harus diisi saat :other bernilai :value.",
	"required_with"        => "Harus diisi saat :values tersedia.",
	"required_with_all"    => "Harus diisi saat :values tersedia.",
	"required_without"     => "Harus diisi saat :values tidak tersedia.",
	"required_without_all" => "Harus diisi saat tidak ada :values yang tersedia.",
	"same"                 => ":attribute dan :other harus sama.",
	"size"                 => array(
		"numeric" => "Ukuran harus :size.",
		"file"    => "Ukuran file harus :size kilobytes.",
		"string"  => "Panjang karakter harus :size .",
		"array"   => "Harus berjumlah :size.",
	),
	"unique"               => ":attribute sudah ada, harap pilih yang lain.",
	"url"                  => "Format tidak valid.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(
		'attribute-name' => array(
			'rule-name' => 'custom-message',
		),
	),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
