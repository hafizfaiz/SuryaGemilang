<?php

return [


    'heading' => '',

    'actions' => [

        'register' => [
            'before' => 'atau',
            'label' => 'daftar akun baru',
        ],

        'request_password_reset' => [
            'label' => 'Lupa kata sandi?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Alamat Email',
        ],

        'password' => [
            'label' => 'Kata Sandi',
        ],

        'remember' => [
            'label' => 'Ingat saya',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Masuk',
            ],

        ],

    ],

    'multi_factor' => [

        'heading' => 'Verifikasi identitas Anda',

        'subheading' => 'Untuk melanjutkan proses masuk, Anda perlu memverifikasi identitas Anda.',

        'form' => [

            'provider' => [
                'label' => 'Bagaimana Anda ingin memverifikasi?',
            ],

            'actions' => [

                'authenticate' => [
                    'label' => 'Konfirmasi masuk',
                ],

            ],

        ],

    ],

    'messages' => [

        'failed' => 'Email atau kata sandi salah.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Terlalu banyak percobaan masuk',
            'body' => 'Silakan coba lagi dalam :seconds detik.',
        ],

    ],

];
