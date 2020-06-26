
<template>
  <div id="app">
    <div class="heading">
      <h1>Blog Posts</h1>
    </div>


    <!-- Add Modal Button -->
    <button type="button" data-toggle="modal" data-target="#exampleModal">
      Add New Blog
    </button>

    <!-- Add Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Blog</h5>
            <button type="button" id="addmodalclose" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label>Title</label>
            <input type="text" id="title" class="form-control" v-model="crud.title" required>
            
            <br>
            <label>Image</label>
            <img :src="image" class="img-responsive" height="70" width="90">
            <input type="file" v-on:change="onImageChange" accept="image/*" class="form-control" required>
            <br>

            <label>Description</label>
            <textarea id="description" class="form-control" v-model="crud.description" required></textarea>
          </div>
          <div class="modal-footer">
             <button @click="create">Add</button>
          </div>
        </div>
      </div>
    </div>
  
      <hr>

    <blog
        v-for="crud in cruds"
        v-bind="crud"
        :key="crud.id"
        @edit="edit"
        @update="update"
        @delete="del"
        @restore="restore">
    </blog>  

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" id="updatemodalclose" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <label>Title</label>
                <input type="text" id="edit_title" class="form-control" v-model="editblog.title" required>

                <br>
                <label>Image</label>
                <img :src="image" class="img-responsive" height="70" width="90">
                <input type="file" v-on:change="onImageChange" accept="image/*" class="form-control" required>
                <br>

                <label>Description</label>
                <textarea id="edit_description" class="form-control" v-model="editblog.description" required></textarea>               

              </div>
              <div class="modal-footer">
                <button @click="update(editblog.id)">Update</button>
              </div>
            </div>
          </div>
         </div>
    </div>
  </div>
</template>

<script>
   
  function Crud({ id, title, description, image, deleted_at }) 
  {
    this.id = id;
    this.title = title;
    this.description = description;
    this.image = image;
    this.deleted_at = deleted_at;
  }

 import Blog from './Blog.vue';

export default {
    data() {
      return {
        cruds: [],
        crud: {
           title: '',
           description: '',
           image: ''
        },
        editblog:
        {
           id:'',
           title: '',
           description: '',
           image:''
        },
        deletedposts: []
      }
    },
    methods: {
      async create() 
      {
        const { data } = await window.axios.post('/api/crud', { 
              title: this.crud.title,
              description: this.crud.description,
              image: this.image })
        this.cruds.push(new Crud(data));

        this.crud.title= '';
        this.crud.description= '';

        $('#addmodalclose').click();
        alert('Added Successfully');
      },
      onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
      },
      createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
      },
      async read() 
      {
        const { data } = await window.axios.get('/api/crud');
        data.forEach(crud => this.cruds.push(new Crud(crud)));
      },
      async edit(id) 
      {
          const data = this.cruds.find(crud => crud.id === id);
          this.editblog.id = data.id; 
          this.editblog.title = data.title;
          this.editblog.description = data.description;
      },
      async update(id) 
      {
        await window.axios.put('/api/crud/'+id, { title: this.editblog.title,
                                                  description: this.editblog.description,
                                                  image: this.image });
                                                  
        this.cruds.find(crud => crud.id === id).title =  this.editblog.title;
        this.cruds.find(crud => crud.id === id).description = this.editblog.description;
        this.cruds.find(crud => crud.id === id).image =  this.image;

        $('#updatemodalclose').click();

        alert('Updated Successfully');
      },
      async del(id) 
      { 
        const data = await window.axios.delete('/api/crud/'+id);     
        let index = this.cruds.findIndex(crud => crud.id === id);
        this.cruds.splice(index, 1);

        alert('Deleted Successfully');
        location.reload();
      },
      async restore(id) 
      { 
        await window.axios.get('/api/crud/'+id+'/edit');
        alert('Restored Successfully');
        location.reload();
      }
    },
    components: {
      Blog
    },
    created() {
      this.read();
    }
  }
</script>


