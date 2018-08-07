<template>
    <div class="container z-depth-3" style="margin-top: 20px; padding: 15px;">
        <a class="waves-effect waves-light btn" @click="chooseImages"><i class="material-icons right">cloud_upload</i>Upload Images</a>
        <input type="file" style="display: none;" id="file-input" accept="image/*" @change="uploadImages" multiple/>
        <div class="images">
            <img class="materialboxed image" width="350" :src="'/storage/' + image" v-for="image in images">
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                images: []
            }
        },
        created() {
            axios.get('/jolly/images').then(res => this.images = res.data);
        },
        methods: {
            chooseImages() {
                $('#file-input').trigger('click');
            },
            uploadImages() {
                let payload = new FormData();
                let fileInput = $('#file-input')[0];
                for (let i = 0; i < fileInput.files.length; i++) {
                    payload.append('images[]', fileInput.files[i], fileInput.files[i].name);
                }
                axios.post('/jolly', payload).then(res => this.images = this.images.concat(res.data));
            },
            initImages() {
                let elems = document.querySelectorAll('.materialboxed');
                M.Materialbox.init(elems);
            }
        },
        watch: {
            images() { setTimeout(this.initImages, 500) }
        }
    }
</script>

<style scoped>
    .images {
        display: flex;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .image {
        margin: 10px;
    }
</style>