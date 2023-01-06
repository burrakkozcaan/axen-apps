<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class AdminProductComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;
    public $image;

    public $price;
    public $quantity;

    public $detail;
    public $status;

    public $newImage;
    public $oldImage;
    public $Product;
    public $category_id;
    /**
     * The validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('products', 'slug')->ignore($this->modelId)],
            'content' => 'required',
            'image' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'status' => 'required',
            'category_id' => 'required',
        ];
    }

    /**
     * The livewire mount function
     *
     * @return void
     */
    public function mount()
    {
        // resetDatas the pagination after reloading the Product
        $this->resetPage();
    }



    public function create()
    {
        $image = $this->newImage->store('public/products');
        $this->validate([
            'newImage' => 'image|mimes:jpg,jpeg,png,svg,gif|max:2048', // 1MB Max
            'title' => 'required',
            'slug' => ['required', Rule::unique('products', 'slug')->ignore($this->modelId)],
            'content' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'status' => 'required',
            'category_id' => 'required',



        ]);
        Product::create([
            'image' => $image,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'status' => $this->status,
            'category_id' => $this->category_id,



        ]);
        $this->reset();
        $this->resetFields();
//        Product::create($this->modelId)->update($this->modelData());

        $this->modalFormVisible = false;
        $this->resetFields();
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'New Page',
            'eventMessage' => 'Another page has been created!',
        ]);

    }
    public function resetFields()
    {
        $this->title = null;
        $this->slug = null;
        $this->content = null;
        $this->price = null;
        $this->quantity = null;
        $this->status = null;
        $this->category_id = null;



    }

    /**
     * The read function.
     *
     * @return void
     */
    public function read()
    {
        return Product::paginate(5);

    }


    /**
     * The update function.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'title' => 'required',
            'slug' => ['required', Rule::unique('products', 'slug')->ignore($this->modelId)],
            'content' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'status' => 'required',
            'category_id' => 'required',

        ]);
        $image = $this->Product->image;
        if($this->newImage){
            $image = $this->newImage->store('public/products');
        }

        $this->Product->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $image,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'status' => $this->status,
            'category_id' => $this->category_id,


        ]);

        $this->reset();
        $this->modalFormVisible = false;


//        Product::find($this->modelId)->update($this->modelData());
//        $this->modalFormVisible = false;
//        $this->reset();
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Page Updated',
            'eventMessage' => 'The page has been updated!',
        ]);
    }

    public function showeditProductModel(){
        $this->Product = Product::find($this->modelId);
        $this->title = $this->Product->title;
        $this->slug = $this->Product->slug;
        $this->content = $this->Product->content;
        $this->oldImage = $this->Product->image;
        $this->quantity = $this->Product->quantity;
        $this->price = $this->Product->price;
        $this->status = $this->Product->status;
        $this->category_id = $this->Product->category_id;


    }


    /**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        $Product = Product::Find($this->modelId);
        Storage::delete($Product->image);
        $Product->delete();
        $this->reset();

        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Deleted Product',
            'eventMessage' => 'The Product (' . $this->modelId . ') has been deleted!',
        ]);
    }

    /**
     * Runs everytime the title
     * variable is updated.
     *
     * @param  mixed $value
     * @return void
     */
    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);

    }



    private function generateSlug($value)
    {
        $dashed = str_replace(" ", "-", $value);
        $lower = strtolower($dashed);
        $this->slug = $lower;
    }

    /**
     * Shows the form modal
     * of the create function.
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->resetValidation();
        $this->reset();
        $this->resetFields();
        $this->modalFormVisible = true;
    }

    /**
     * Shows the form modal
     * in update mode.
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id)
    {
        $this->resetValidation();
        $this->reset();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
        $this->showeditProductModel();
    }

    /**
     * Shows the delete confirmation modal.
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id)
    {
        $this->modelId = $id;

        $this->modalConfirmDeleteVisible = true;
    }

    /**
     * Loads the model data
     * of this component.
     *
     * @return void
     */
    public function loadModel()
    {
        $products = Product::find($this->modelId);
        $this->title = $products->title;
        $this->slug = $products->slug;
        $this->content = $products->content;
        $this->oldImage = $products->image;
        $this->quantity = $products->quantity;
        $this->price = $products->price;
        $this->status = $products->status;
        $this->category_id = $products->category_id;


    }

    /**
     * The data for the model mapped
     * in this component.
     *
     * @return array
     */
    public function modelData()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'status' => $this->status,
            'category_id' => $this->category_id,
        ];
    }


    /**
     * Dispatch event
     *
     * @return void
     */
    public function dispatchEvent()
    {
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Sample Event',
            'eventMessage' => 'You have a sample event notification!',
        ]);
    }

    public function render()
    {

        $products =  [
            'products' => $this->read(),

        ];
        $categories = Category::all();


        return view('livewire.admin.admin-product-component', $products, ['categories' => $categories] )->layout('layouts.admin');
    }
}
