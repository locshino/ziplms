<?php

namespace Database\Seeders\Concerns;

trait HasSampleFiles
{
    /**
     * Get an array of sample files for seeding.
     */
    protected function getSampleFiles(): array
    {
        return [
            [
                'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                'name' => 'dummy.pdf',
            ],
            [
                'url' => 'https://raw.githubusercontent.com/recurser/exif-orientation-examples/master/Landscape_1.jpg',
                'name' => 'landscape.jpg',
            ],
            [
                'url' => 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
                'name' => 'google.png',
            ],
            // Thêm URL cho các file .docx, .xlsx, .pptx nếu có sẵn file mẫu nhỏ, đáng tin cậy
            // Ví dụ (đảm bảo các URL này còn hoạt động và file nhỏ):
            // ['url' => 'https://calibre-ebook.com/downloads/demos/demo.docx', 'name' => 'demo.docx'],
            // ['url' => 'https://file-examples.com/storage/fe125dba13392b261039e6d/2017/02/file_example_XLSX_10.xlsx', 'name' => 'example.xlsx'],
        ];
    }
}
