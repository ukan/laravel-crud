<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Crud;

use Image; 
use Illuminate\Support\Facades\File;

use App\Http\Requests\CrudRequest;

class CrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Crud::orderBy('id', 'DESC')->paginate(3);
        
        return view('show')->with('datas', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrudRequest $request)
    {
        /*$this->validate($request, [
            'judul' => 'required',
            'isi' => 'required'
        ]);*/

        $tambah = new Crud();
        $tambah->judul = $request['judul'];
        $tambah->isi = $request['isi'];

        if($request->file('gambar')==""){

        }else{
            
            $GalleryImages = new Crud();
            // Disini proses mendapatkan judul dan memindahkan letak gambar ke folder image
           $file = $request->file('gambar');
           $fileName   = $file->getClientOriginalName();
           $imageId = $GalleryImages->id;
           $extension = $request->file('gambar')->getClientOriginalExtension();

           $image = Image::make($file->getRealPath());
           //save image with thumbnail
           $image->save(public_path().'/uploads/image/'.$imageId.'-'.$fileName)->resize(200, 300)->save(public_path().'/uploads/image/thumb/'.'thumb-'.$imageId.'-'.$fileName);

           $tambah->gambar=$imageId.'-'.$fileName;
        }
        $tambah->save();

        return redirect()->to('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tampilkan = Crud::find($id);
        return view('tampil')->with('tampilkan', $tampilkan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tampiledit = Crud::where('id', $id)->first();
        return view('edit')->with('tampiledit', $tampiledit);
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
        $update = Crud::where('id', $id)->first();
        $update->judul = $request['judul'];
        $update->isi = $request['isi'];

        $GalleryImages = Crud::findOrFail($id);
          if (!empty($request->file('gambar'))){

           $file = $request->file('gambar');
           $fileName   = $file->getClientOriginalName();
           $imageId = $GalleryImages->id;
           $extension = $request->file('gambar')->getClientOriginalExtension();

           $image = Image::make($file->getRealPath());
           //save image with thumbnail
           $image->save(public_path().'/uploads/image/'.$imageId.'-'.$fileName)->resize(200, 300)->save(public_path().'/uploads/image/thumb/'.'thumb-'.$imageId.'-'.$fileName);
            
            $update->gambar=$imageId.'-'.$fileName;
            $update->update();
        }
        return redirect()->to('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hapus = Crud::find($id);
        $hapus->delete();

        return redirect()->to('/');
    }
}
