<template>
  <div class="crud">
     <div class="col-12"> 
      <img :src="image" height="70" width="90">
      <h3>{{ title }}</h3>
      <p>{{ description }}</p>
    </div>

    <div v-if="deleted_at === null">
          <!-- Edit Modal Button-->
        <button data-toggle="modal" data-target="#editModal" @click="edit">Edit</button>
        <button @click="del">Delete</button>
    </div>
    <div v-else>
        <font color="red">Deleted Post </font> - <button @click="restore">Restore</button>
    </div>

    <hr>
  </div> 
</template>

<script>
  export default {
    computed: {
    },
    methods: {
      edit(){
        this.$emit('edit', this.id);
      },
      restore(){
        this.$emit('restore', this.id);
      },
      del() {
        this.$emit('delete', this.id);
      }
    },
    props: ['id', 'title', 'description', 'image', 'deleted_at']
  }
</script>