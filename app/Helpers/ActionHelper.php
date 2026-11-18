<?php

namespace App\Helpers;

use Illuminate\Support\HtmlString;

class ActionHelper
{
    /**
     * Tombol Edit seragam (Warna Amber/Warning modern, icon fa-pen-to-square)
     */
    public static function edit(string $url, string $text = 'Edit', string $title = 'Edit Data', string $extraClass = ''): HtmlString
    {
        $html = sprintf(
            '<a href="%s" class="btn btn-warning btn-sm btn-action btn-action-warning %s" title="%s" data-toggle="tooltip">
                <i class="fa-solid fa-pen-to-square mr-1"></i> <span class="d-none d-md-inline">%s</span>
            </a>',
            e($url),
            e($extraClass),
            e($title),
            e($text)
        );

        return new HtmlString($html);
    }

    /**
     * Tombol Hapus seragam dengan Form DELETE & SweetAlert
     */
    public static function delete(string $url, string $text = 'Hapus', string $title = 'Hapus Data', string $extraClass = ''): HtmlString
    {
        $csrf = csrf_field();
        $method = method_field('DELETE');

        $html = sprintf(
            '<form action="%s" method="POST" class="d-inline form-delete">
                %s
                %s
                <button type="submit" class="btn btn-danger btn-sm btn-action btn-delete %s" id="btn-delete" title="%s" data-toggle="tooltip">
                    <i class="fa-solid fa-trash-can mr-1"></i> <span class="d-none d-md-inline">%s</span>
                </button>
            </form>',
            e($url),
            $csrf,
            $method,
            e($extraClass),
            e($title),
            e($text)
        );

        return new HtmlString($html);
    }

    /**
     * Tombol Detail / Lihat seragam (Warna Info/Cyan)
     */
    public static function view(string $url, string $text = 'Detail', string $title = 'Lihat Detail', string $extraClass = ''): HtmlString
    {
        $html = sprintf(
            '<a href="%s" class="btn btn-info btn-sm btn-action text-white %s" title="%s" data-toggle="tooltip">
                <i class="fa-solid fa-eye mr-1"></i> <span class="d-none d-md-inline">%s</span>
            </a>',
            e($url),
            e($extraClass),
            e($title),
            e($text)
        );

        return new HtmlString($html);
    }

    /**
     * Gabungan Aksi Edit & Hapus dalam satu baris rapi
     */
    public static function actions(string $editUrl, string $deleteUrl, string $extraButtons = ''): HtmlString
    {
        $editBtn = self::edit($editUrl);
        $deleteBtn = self::delete($deleteUrl);

        $html = sprintf(
            '<div class="btn-action-group d-flex justify-content-center align-items-center flex-nowrap" style="gap: 5px;">
                %s
                %s
                %s
            </div>',
            $extraButtons,
            $editBtn,
            $deleteBtn
        );

        return new HtmlString($html);
    }

    /**
     * Tombol Tambah Data (Warna Primary Blue)
     */
    public static function create(string $url, string $text = 'Tambah', string $icon = 'fa-solid fa-plus', string $extraClass = ''): HtmlString
    {
        $html = sprintf(
            '<a href="%s" class="btn btn-primary btn-sm btn-action %s">
                <i class="%s mr-1"></i> %s
            </a>',
            e($url),
            e($extraClass),
            e($icon),
            e($text)
        );

        return new HtmlString($html);
    }

    /**
     * Tombol Cetak Laporan (Warna Success Green)
     */
    public static function print(string $url, string $text = 'Cetak Laporan', string $target = '_blank', string $extraClass = ''): HtmlString
    {
        $html = sprintf(
            '<a href="%s" target="%s" class="btn btn-success btn-sm btn-action %s">
                <i class="fa-solid fa-print mr-1"></i> %s
            </a>',
            e($url),
            e($target),
            e($extraClass),
            e($text)
        );

        return new HtmlString($html);
    }

    /**
     * Tombol Kembali Form (Warna Secondary)
     */
    public static function back(string $url, string $text = 'Kembali', string $extraClass = ''): HtmlString
    {
        $html = sprintf(
            '<a href="%s" class="btn btn-secondary btn-sm btn-action %s">
                <i class="fa-solid fa-arrow-left mr-1"></i> %s
            </a>',
            e($url),
            e($extraClass),
            e($text)
        );

        return new HtmlString($html);
    }

    /**
     * Tombol Simpan Form (Warna Success Green)
     */
    public static function save(string $text = 'Simpan', string $icon = 'fa-solid fa-floppy-disk', string $extraClass = ''): HtmlString
    {
        $html = sprintf(
            '<button type="submit" class="btn btn-success btn-sm btn-action %s">
                <i class="%s mr-1"></i> %s
            </button>',
            e($extraClass),
            e($icon),
            e($text)
        );

        return new HtmlString($html);
    }

    /**
     * Tombol Reset Form (Warna Secondary Gray)
     */
    public static function reset(string $text = 'Reset', string $icon = 'fa-solid fa-arrows-rotate', string $extraClass = ''): HtmlString
    {
        $html = sprintf(
            '<button type="reset" class="btn btn-secondary btn-sm btn-action mr-1 %s">
                <i class="%s mr-1"></i> %s
            </button>',
            e($extraClass),
            e($icon),
            e($text)
        );

        return new HtmlString($html);
    }
}

