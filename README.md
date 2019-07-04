## Updates in progress

Right now I'm tracking version compatibility and as long as possible I'll update this page to inform compatible versions.

# Dompdf

Compatibility of tibonilab/pdf-lumen-bundle that is a compatibility fork of k98jurz/pdf-lumen that is a conversion of Thujohn/Pdf for Laravel Lumen 5.* :3

Simple Dompdf wrapper package (uses Dompdf stable version 0.8.*)


## Installation Lumen >= 5.8.*

Add `pichoweb/pdf-lumen-bundle` to `composer.json`.

```
"pichoweb/pdf-lumen-bundle": "^3.0"
```

## Installation Lumen >= 5.4.*

Add `pichoweb/pdf-lumen-bundle` to `composer.json`.

```
"pichoweb/pdf-lumen-bundle": "2.0.0"
```

## Installation Lumen <= 5.3.*

Add `pichoweb/pdf-lumen-bundle` to `composer.json`.

```
"pichoweb/pdf-lumen-bundle": "1.0.0"
```

After require right version tag for your Lumen installation run `composer update` to pull down the latest version of Pdf.

Open up `bootstrap/app.php` and add the service provider.
```php
	$app->register('pichoweb\Pdf\PdfServiceProvider');
```

And add the alias.
```php
    class_alias('pichoweb\Pdf\PdfFacade', 'PDF');
```


## Usage

Show a PDF
```php
$app->get('/', function () {
	$html = '<html><body>'
			. '<p>Put your html here, or generate it with your favourite '
			. 'templating system.</p>'
			. '</body></html>';
	return PDF::load($html, 'A4', 'portrait')->show();
});
```

Download a PDF
```php
$app->get('/', function () {
	$html = '<html><body>'
			. '<p>Put your html here, or generate it with your favourite '
			. 'templating system.</p>'
			. '</body></html>';
	return PDF::load($html, 'A4', 'portrait')->download('my_pdf');
});
```

Returns a PDF as a string
```php
$app->get('/', function () {
	$html = '<html><body>'
			. '<p>Put your html here, or generate it with your favourite '
			. 'templating system.</p>'
			. '</body></html>';
	$pdf = PDF::load($html, 'A4', 'portrait')->output();
});
```

Multiple PDFs
```php
for ($i=1;$i<=2;$i++) {
	$pdf = new \k98kurz\Pdf\Pdf();
	$content = $pdf->load(View::make('pdf.image'))->output();
	File::put(public_path('test'.$i.'.pdf'), $content);
}
PDF::clear();
```


## Examples

Save the PDF to a file in a specific folder, and then mail it as attachement.
By @w0rldart

```php
define('BUDGETS_DIR', public_path('uploads/budgets')); // I define this in a constants.php file

if (!is_dir(BUDGETS_DIR)){
	mkdir(BUDGETS_DIR, 0755, true);
}

$outputName = str_random(10); // str_random is a [Laravel helper](http://laravel.com/docs/helpers#strings)
$pdfPath = BUDGETS_DIR.'/'.$outputName.'.pdf';
File::put($pdfPath, PDF::load($view, 'A4', 'portrait')->output());

Mail::send('emails.pdf', $data, function($message) use ($pdfPath){
	$message->from('us@example.com', 'Laravel');
	$message->to('you@example.com');
	$message->attach($pdfPath);
});
```
