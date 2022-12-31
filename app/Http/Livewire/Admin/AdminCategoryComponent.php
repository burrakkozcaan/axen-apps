<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\WithPagination;


class AdminCategoryComponent extends Component
{
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

    /**
     * The validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('categories', 'slug')->ignore($this->modelId)],
            'content' => 'required',
        ];
    }

    /**
     * The livewire mount function
     *
     * @return void
     */
    public function mount()
    {
        // resetDatas the pagination after reloading the Category
        $this->resetPage();
    }


    public function create()
    {
        $this->validate();
//        Category::create($this->modelId)->update($this->modelData());
        Category::create($this->modelData());
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
    }

    /**
     * The read function.
     *
     * @return void
     */
   public function read()
    {
        return Category::paginate(5);

       }


    /**
     * The update function.
     *
     * @return void
     */
    public function update()
    {

        $this->validate();
        Category::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Page Updated',
            'eventMessage' => 'The page has been updated!',
        ]);
    }

    /**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        Category::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Deleted Category',
            'eventMessage' => 'The Category (' . $this->modelId . ') has been deleted!',
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
        $categories = Category::find($this->modelId);
        $this->title = $categories->title;
        $this->slug = $categories->slug;
        $this->content = $categories->content;

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

       $categories =  [
         'categories' => $this->read(),
     ];

//        $categories = Category::latest()->paginate(5);
        return view('livewire.admin.admin-category-component', $categories)
            ->layout('layouts.admin');
    }
}
