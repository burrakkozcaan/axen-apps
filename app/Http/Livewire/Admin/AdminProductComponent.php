<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Component;


class AdminProductComponent extends Component
{

    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $title;
    public $slug;
    public $price;
    public $quantity;
    public $sale_price;
    public $detail;
    public $image;
    public $category_id;
    public $user_id;
    public $status;
    public $modelId;
    public $content;
    public $keywords;


    use WithFileUploads;

    /**
     * The validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required | unique:products',
            'slug' => ['required', Rule::unique('products', 'slug')->ignore($this->modelId)],
            'price' => 'required | numeric',
            'quantity' => 'required | numeric',
            'sale_price' => 'required | numeric',
            'detail' => 'required',
            'image' => 'required | image | mimes:png,jpg,jpeg | max:1048',
            'user_id' => 'required',
            'status' => 'required',
            'content' => 'required | string',
            'keywords' => 'required',
            'category_id' => 'required',

        ];
    }

    public function upload()
    {

        $this->validate([
            'image' => 'required|image',
        ]);
        $this->image->store('images', 'public');

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
        $this->validate();
       Product::create($this->modelData());
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible = true;
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'New Page',
            'eventMessage' => 'Another page has been created!',
        ]);



    }
    public function resetFields()
    {
        $this->title = null;
        $this->slug = null;
        $this->price = null;
        $this->quantity = null;
        $this->sale_price = null;
        $this->detail = null;
        $this->image = null;
        $this->user_id = null;
        $this->status = null;
        $this->content = null;
        $this->keywords = null;
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

//        $this->validate();
//        Product::find($this->modelData());
//        $this->modalFormVisible = false;
//        $this->reset();
//        $this->dispatchBrowserEvent('event-notification', [
//            'eventName' => 'Page Updated',
//            'eventMessage' => 'The page has been updated!',
//        ]);

        dd($this->modelData());
    }

    /**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        Product::destroy($this->modelId);
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
        $this->price = $products->price;
        $this->quantity = $products->quantity;
        $this->sale_price = $products->sale_price;
        $this->detail = $products->detail;
        $this->image = $products->image;
        $this->user_id = $products->user_id;
        $this->status = $products->status;
        $this->content = $products->content;
        $this->keywords = $products->keywords;
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
            'price' => $this->price,
            'quantity' => $this->quantity,
            'sale_price' => $this->sale_price,
            'detail' => $this->detail,
            'image' => $this->image,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'content' => $this->content,
            'keywords' => $this->keywords,
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
        return view('livewire.admin.admin-product-component', $products)->layout('layouts.admin');
    }
}
