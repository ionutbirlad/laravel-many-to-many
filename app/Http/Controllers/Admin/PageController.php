<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\User;
use App\Page;
use App\Category;
use App\Tag;
use App\Photo;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $categories = Category::all();
      $tags = Tag::all();
      $photos = Photo::all();
      return view('admin.pages.create', compact('categories', 'tags', 'photos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      // Posso anche scegliere di non mettere i dati della request in una variabile
      $data = $request->all();
      // Posso anche scegliere di non mettere i dati della request in una variabile

      //dd($request->all());

      //-------------------validazione-------------------
      $validator = Validator::make($data, [
          'title' => 'required|max:200',
          'body' => 'required',
          'category_id' => 'required|exists:categories,id',
          'tags' => 'required|array',
          'photos' => 'required|array',
          'tags.*' => 'exists:tags,id',
          'photos.*' => 'exists:photos,id'
      ]);

      if ($validator->fails()) {
          return redirect()->route('admin.pages.create')
          ->withErrors($validator)
          ->withInput();
      }
      //-------------------validazione-------------------

      // Instanzio, "fillo" e salvo la nuova instanza con i dati della request
      $page = new Page;
      $data['slug'] = Str::slug($data['title'], '-');
      $data['user_id'] = Auth::id();
      $page->fill($data);
      $saved = $page->save();
      // Instanzio, "fillo" e salvo la nuova instanza con i dati della request

      if (!$saved) {
        dd('Qualcosa è andato stprto!');
      }

      // Ricordarmi di compilare anche la tabella pivot, ma sempre dopo aver "fillato" ovviamente
      $page->tags()->attach($data['tags']);
      $page->photos()->attach($data['photos']);
      // Ricordarmi di compilare anche la tabella pivot, ma sempre dopo aver "fillato" ovviamente

      return redirect()->route('admin.pages.show', $page->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Con FindOrFail gestisce da solo la 404 senza doverlo fare a mano
        $page = Page::findOrFail($id);
        // Con FindOrFail gestisce da solo la 404 senza doverlo fare a mano

        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        $photos = Photo::al();

        return view('admin.pages.edit', compact('page', 'categories', 'tags', 'photos'));
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
        //
    }
}
