<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PDFFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;


class PdfApiController extends Controller
{       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         // Recuperar todos los registros de PDFs
         $pdfFiles = PDFFile::all();

         // Verificar si la colección está vacía
         if ($pdfFiles->isEmpty()) {
             return response()->json([
                 'message' => 'No PDF files found.',
             ], 404);
         }
 
         // Devolver la lista de PDFs como JSON
         return response()->json([
             'message' => 'Successfully retrieved all PDF files.',
             'data' => $pdfFiles,
         ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pdfFile = PDFFile::findOrFail($id);
        return response()->json($pdfFile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        
        $file = PDFFile::findOrFail($id);
        // Eliminar el prefijo 'public/' de la ruta almacenada
        $path = str_replace('public/', '', $file->path);
    
        if (Storage::disk('public')->exists($path)) {
            if (Storage::disk('public')->delete($path)) {
                $file->delete();
                return response()->json(['message' => 'File deleted successfully.']);
            } else {
                return response()->json(['error' => 'Failed to delete the file.'], 500);
            }
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }

    }

   
        public function upload(Request $request)
{   
    //$user = Auth::user();
    \Log::info('Received upload request.');

    $validated = $request->validate([
        'file' => 'required|file|mimes:pdf|max:51200',
    ]);

    if (!$request->hasFile('file')) {
        \Log::error('No file was uploaded.');
        return response()->json(['error' => 'No file was uploaded'], 400);
    }

    $file = $request->file('file');
    if (!$file->isValid()) {
        \Log::error('File is not valid.');
        return response()->json(['error' => 'File is not valid'], 400);
    }

    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('public/pdfs', $filename);

    if (!$path) {
        \Log::error('Failed to store the file.');
        return response()->json(['error' => 'Failed to store the file'], 500);
    }

    \Log::info('File stored at: ' . $path);

    try {
        $pdfFile = PDFFile::create([
            'name' => $filename,
            'path' => $path,
        ]);
    } catch (\Exception $e) {
        \Log::error('Failed to save file info in database: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to save file info in database'], 500);
    }

    return response()->json([
        'message' => 'arcivo cargado correctamente',
        'file' => $pdfFile,
    ]);
}

public function download($id)
{
    $pdfFile = PDFFile::findOrFail($id);
    // Remueve 'public/' del path porque storage_path('app/public') ya está apuntando a 'storage/app/public'
    $correctPath = str_replace('public/', '', $pdfFile->path);
    $pathToFile = storage_path('app/public/' . $correctPath);

    if (!file_exists($pathToFile)) {
        return response()->json(['error' => 'File not found.', 'path' => $pathToFile], 404);
    }

    return response()->download($pathToFile, $pdfFile->name);
}

}
