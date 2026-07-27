<?php

return [

    'select' => [

        'actions' => [

            'create_option' => [

                'label' => 'Buat',

                'modal' => [

                    'heading' => 'Buat',

                    'actions' => [

                        'create' => [
                            'label' => 'Buat',
                        ],

                        'create_another' => [
                            'label' => 'Buat & buat lagi',
                        ],

                    ],

                ],

            ],

            'edit_option' => [

                'label' => 'Ubah',

                'modal' => [

                    'heading' => 'Ubah',

                    'actions' => [

                        'save' => [
                            'label' => 'Simpan',
                        ],

                    ],

                ],

            ],

        ],

        'boolean' => [
            'true' => 'Ya',
            'false' => 'Tidak',
        ],

        'loading_message' => 'Memuat...',

        'max_items_message' => 'Hanya :count yang dapat dipilih.',

        'no_options_message' => 'Tidak ada pilihan tersedia.',

        'no_search_results_message' => 'Tidak ada pilihan yang cocok dengan pencarian Anda.',

        'placeholder' => 'Pilih salah satu',

        'searching_message' => 'Mencari...',

        'search_prompt' => 'Mulai ketik untuk mencari...',

    ],

    'text_input' => [

        'actions' => [

            'copy' => [
                'label' => 'Salin',
                'message' => 'Tersalin',
            ],

            'hide_password' => [
                'label' => 'Sembunyikan kata sandi',
            ],

            'show_password' => [
                'label' => 'Tampilkan kata sandi',
            ],

        ],

    ],

    'toggle_buttons' => [

        'boolean' => [
            'true' => 'Ya',
            'false' => 'Tidak',
        ],

    ],

    'checkbox_list' => [

        'actions' => [

            'deselect_all' => [
                'label' => 'Batalkan semua',
            ],

            'select_all' => [
                'label' => 'Pilih semua',
            ],

        ],

    ],

    'file_upload' => [

        'editor' => [

            'actions' => [

                'cancel' => [
                    'label' => 'Batal',
                ],

                'drag_crop' => [
                    'label' => 'Mode seret "potong"',
                ],

                'drag_move' => [
                    'label' => 'Mode seret "pindahkan"',
                ],

                'flip_horizontal' => [
                    'label' => 'Balik gambar secara horizontal',
                ],

                'flip_vertical' => [
                    'label' => 'Balik gambar secara vertikal',
                ],

                'move_down' => [
                    'label' => 'Pindahkan gambar ke bawah',
                ],

                'move_left' => [
                    'label' => 'Pindahkan gambar ke kiri',
                ],

                'move_right' => [
                    'label' => 'Pindahkan gambar ke kanan',
                ],

                'move_up' => [
                    'label' => 'Pindahkan gambar ke atas',
                ],

                'reset' => [
                    'label' => 'Atur ulang',
                ],

                'rotate_left' => [
                    'label' => 'Putar gambar ke kiri',
                ],

                'rotate_right' => [
                    'label' => 'Putar gambar ke kanan',
                ],

                'set_aspect_ratio' => [
                    'label' => 'Atur rasio aspek ke :ratio',
                ],

                'save' => [
                    'label' => 'Simpan',
                ],

                'zoom_100' => [
                    'label' => 'Perbesar gambar ke 100%',
                ],

                'zoom_in' => [
                    'label' => 'Perbesar',
                ],

                'zoom_out' => [
                    'label' => 'Perkecil',
                ],

            ],

            'fields' => [

                'height' => [
                    'label' => 'Tinggi',
                    'unit' => 'px',
                ],

                'rotation' => [
                    'label' => 'Rotasi',
                    'unit' => 'deg',
                ],

                'width' => [
                    'label' => 'Lebar',
                    'unit' => 'px',
                ],

                'x_position' => [
                    'label' => 'X',
                    'unit' => 'px',
                ],

                'y_position' => [
                    'label' => 'Y',
                    'unit' => 'px',
                ],

            ],

            'aspect_ratios' => [

                'label' => 'Rasio aspek',

                'no_fixed' => [
                    'label' => 'Bebas',
                ],

            ],

            'svg' => [

                'messages' => [
                    'confirmation' => 'Mengedit file SVG tidak disarankan karena dapat mengakibatkan penurunan kualitas saat diskalakan.\n Apakah Anda yakin ingin melanjutkan?',
                    'disabled' => 'Mengedit file SVG dinonaktifkan karena dapat mengakibatkan penurunan kualitas saat diskalakan.',
                ],

            ],

        ],

    ],

    'key_value' => [

        'actions' => [

            'add' => [
                'label' => 'Tambah baris',
            ],

            'delete' => [
                'label' => 'Hapus baris',
            ],

            'reorder' => [
                'label' => 'Urutkan baris',
            ],

        ],

        'fields' => [

            'key' => [
                'label' => 'Kunci',
            ],

            'value' => [
                'label' => 'Nilai',
            ],

        ],

    ],

    'repeater' => [

        'actions' => [

            'add' => [
                'label' => 'Tambah ke :label',
            ],

            'add_between' => [
                'label' => 'Sisipkan di antara',
            ],

            'delete' => [
                'label' => 'Hapus',
            ],

            'clone' => [
                'label' => 'Duplikat',
            ],

            'reorder' => [
                'label' => 'Pindahkan',
            ],

            'move_down' => [
                'label' => 'Pindah ke bawah',
            ],

            'move_up' => [
                'label' => 'Pindah ke atas',
            ],

            'collapse' => [
                'label' => 'Tutup',
            ],

            'expand' => [
                'label' => 'Buka',
            ],

            'collapse_all' => [
                'label' => 'Tutup semua',
            ],

            'expand_all' => [
                'label' => 'Buka semua',
            ],

        ],

    ],

    'radio' => [

        'boolean' => [
            'true' => 'Ya',
            'false' => 'Tidak',
        ],

    ],

    'tags_input' => [

        'actions' => [

            'delete' => [
                'label' => 'Hapus',
            ],

        ],

        'placeholder' => 'Tag baru',

    ],

];
