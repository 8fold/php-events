<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

use Eightfold\HTMLBuilder\Element;
use Eightfold\HTMLBuilder\Document;

use Eightfold\Events\Grid;

$psr17Factory = new Psr17Factory();

$request = (new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
))->fromGlobals();

$path = $request->getUri()->getPath();

$dataPath = __DIR__ . '/data'; // where the events live

$pathParts = explode('/', $path);
$filteredPath = array_values(array_filter($pathParts));

if (str_ends_with($path, 'ef-events.css')) {
    $css = file_get_contents('./' . $path);
    (new SapiEmitter())->emit(
        new Response(
            status: 200,
            headers: ['Content-type' => 'text/css'],
            body: Stream::create($css)
        )
    );
    exit();

} elseif (str_ends_with($path, 'ef-events.min.js')) {
    $js = file_get_contents('./' . $path);
    (new SapiEmitter())->emit(
        new Response(
            status: 200,
            headers: ['Content-type' => 'application/javascript'],
            body: Stream::create($js)
        )
    );
    exit();

} elseif (count($filteredPath) > 2) {
    (new SapiEmitter())->emit(
        new Response(
            status: 404,
            body: Stream::create('404 equivalent: Too many URI parts.')
        )
    );
    exit();

} elseif ($path === '/') {
    // No view for root. User SHOULD be redirected current year and month.
    $redirect = '/' . date('Y') . '/' . date('m') . '/';
    (new SapiEmitter())->emit(
        new Response(
            status: 302,
            headers: ['Location' => $redirect]
        )
    );
    exit();

} elseif (count($filteredPath) === 1) {
    // Year view.
    $year  = intval($filteredPath[0]);

    $grid = Grid::forYear($dataPath, $year)->unfold();
    (new SapiEmitter())->emit(
        new Response(
            status: 200,
            body: Stream::create(
                Document::create(
                    '8fold Events Example'
                )->head(
                    Element::link()->props('rel stylesheet', 'href /ef-events.css', 'type text/css'),
                    Element::script()->props('src /ef-events.min.js')
                )->body(
                    $grid
                )->build()
            )
        )
    );
    exit();

} elseif (count($filteredPath) === 2) {
    // Month view
    $year  = intval($filteredPath[0]);
    $month = intval($filteredPath[1]);

    $grid = Grid::forMonth($dataPath, $year, $month)->unfold();
    (new SapiEmitter())->emit(
        new Response(
            status: 200,
            body: Stream::create(
                Document::create(
                    '8fold Events Example'
                )->head(
                    Element::link()->props('rel stylesheet', 'href /ef-events.css', 'type text/css'),
                    Element::script()->props('src /ef-events.min.js')
                )->body(
                    $grid
                )->build()
            )
        )
    );
    exit();

}
