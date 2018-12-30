<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function downloadXml(Product $product)
    {
        $result = $this->getXml();
        if ($result->isEmpty()) {
            // redirected because it is opening a new tab when is empty.
            return redirect('/products')->with('flash', 'Your product table is empty.');
        }
        // $result = $result->toArray();

        $filename = auth()->id().'products.xml';
        Storage::disk('public')->put($filename, $result);
        // sleep(0.1);

        // Check if file exists in app/storage/file folder
        $file_path = storage_path() . "/app/public/" . $filename;
        $headers = array(
            'Content-Type: xml',
            'Content-Disposition: attachment; filename='.$filename,
        );
        if (file_exists($file_path)) {
            // Send Download
            return \Response::download($file_path, $filename, $headers);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function getXml()
    {
        $fields = ['user_id', 'created_at', 'updated_at'];

        $products =Product::where('user_id', auth()->id())
               ->exclude($fields)
               ->get();

        $xml = ArrayToXml::convert(['Product' => $products->toArray()], 'Poducts', true, 'UTF-8', '1.0');

        return \Response::make($xml, 200)->header('Content-Type', 'text/xml');
        // return Product::where('user_id', auth()->id())
        //       ->exclude($fields)
        //       ->get()
        //       ->map(function (Product $product) {
        //           return ArrayToXml::convert($product->attributesToArray(), 'product');
        //       });
    }
}
