<template>
    <Head>
        <title>Edit Soal Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link
                    :href="`/admin/exams/${exam.id}`"
                    class="btn btn-md btn-primary border-0 shadow mb-3"
                    type="button"
                    ><i class="fa fa-long-arrow-alt-left me-2"></i>
                    Kembali</Link
                >
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5>
                            <i class="fa fa-question-circle"></i> Edit Soal
                            Ujian
                        </h5>
                        <hr />
                        <form @submit.prevent="submit">
                            <div class="table-responsive mb-4">
                                <table
                                    class="table table-bordered table-centered table-nowrap mb-0 rounded"
                                >
                                    <tbody>
                                        <tr>
                                            <td
                                                style="width: 20%"
                                                class="fw-bold"
                                            >
                                                Soal
                                            </td>
                                            <td>
                                                <Editor
                                                    api-key="bvu9lokg76dk4mx0sipczxrdbcmps5ll9px9olsfpqjywybo"
                                                    v-model="form.question"
                                                    :init="{
                                                        menubar: false,
                                                        plugins:
                                                            'lists link image emoticons',
                                                        toolbar:
                                                            'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                    }"
                                                />
                                                <div class="mt-2">
                                                    <label class="form-label"
                                                        >Gambar Soal
                                                        (Optional)</label
                                                    >
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        @change="
                                                            form.question_image =
                                                                $event.target.files[0]
                                                        "
                                                        accept="image/*"
                                                    />
                                                    <div
                                                        v-if="
                                                            question.question_image
                                                        "
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="`/storage/${question.question_image}`"
                                                            alt="Question Image"
                                                            style="
                                                                max-width: 200px;
                                                            "
                                                            class="img-thumbnail"
                                                        />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="width: 20%"
                                                class="fw-bold"
                                            >
                                                Pilihan A
                                            </td>
                                            <td>
                                                <Editor
                                                    api-key="bvu9lokg76dk4mx0sipczxrdbcmps5ll9px9olsfpqjywybo"
                                                    v-model="form.option_1"
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins:
                                                            'lists link image emoticons',
                                                        toolbar:
                                                            'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                    }"
                                                />
                                                <div class="mt-2">
                                                    <label class="form-label"
                                                        >Gambar Pilihan A
                                                        (Optional)</label
                                                    >
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        @change="
                                                            form.option_1_image =
                                                                $event.target.files[0]
                                                        "
                                                        accept="image/*"
                                                    />
                                                    <div
                                                        v-if="
                                                            question.option_1_image
                                                        "
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="`/storage/${question.option_1_image}`"
                                                            alt="Option 1 Image"
                                                            style="
                                                                max-width: 200px;
                                                            "
                                                            class="img-thumbnail"
                                                        />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="width: 20%"
                                                class="fw-bold"
                                            >
                                                Pilihan B
                                            </td>
                                            <td>
                                                <Editor
                                                    api-key="bvu9lokg76dk4mx0sipczxrdbcmps5ll9px9olsfpqjywybo"
                                                    v-model="form.option_2"
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins:
                                                            'lists link image emoticons',
                                                        toolbar:
                                                            'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                    }"
                                                />
                                                <div class="mt-2">
                                                    <label class="form-label"
                                                        >Gambar Pilihan B
                                                        (Optional)</label
                                                    >
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        @change="
                                                            form.option_2_image =
                                                                $event.target.files[0]
                                                        "
                                                        accept="image/*"
                                                    />
                                                    <div
                                                        v-if="
                                                            question.option_2_image
                                                        "
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="`/storage/${question.option_2_image}`"
                                                            alt="Option 2 Image"
                                                            style="
                                                                max-width: 200px;
                                                            "
                                                            class="img-thumbnail"
                                                        />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="width: 20%"
                                                class="fw-bold"
                                            >
                                                Pilihan C
                                            </td>
                                            <td>
                                                <Editor
                                                    api-key="bvu9lokg76dk4mx0sipczxrdbcmps5ll9px9olsfpqjywybo"
                                                    v-model="form.option_3"
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins:
                                                            'lists link image emoticons',
                                                        toolbar:
                                                            'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                    }"
                                                />
                                                <div class="mt-2">
                                                    <label class="form-label"
                                                        >Gambar Pilihan C
                                                        (Optional)</label
                                                    >
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        @change="
                                                            form.option_3_image =
                                                                $event.target.files[0]
                                                        "
                                                        accept="image/*"
                                                    />
                                                    <div
                                                        v-if="
                                                            question.option_3_image
                                                        "
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="`/storage/${question.option_3_image}`"
                                                            alt="Option 3 Image"
                                                            style="
                                                                max-width: 200px;
                                                            "
                                                            class="img-thumbnail"
                                                        />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="width: 20%"
                                                class="fw-bold"
                                            >
                                                Pilihan D
                                            </td>
                                            <td>
                                                <Editor
                                                    api-key="bvu9lokg76dk4mx0sipczxrdbcmps5ll9px9olsfpqjywybo"
                                                    v-model="form.option_4"
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins:
                                                            'lists link image emoticons',
                                                        toolbar:
                                                            'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                    }"
                                                />
                                                <div class="mt-2">
                                                    <label class="form-label"
                                                        >Gambar Pilihan D
                                                        (Optional)</label
                                                    >
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        @change="
                                                            form.option_4_image =
                                                                $event.target.files[0]
                                                        "
                                                        accept="image/*"
                                                    />
                                                    <div
                                                        v-if="
                                                            question.option_4_image
                                                        "
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="`/storage/${question.option_4_image}`"
                                                            alt="Option 4 Image"
                                                            style="
                                                                max-width: 200px;
                                                            "
                                                            class="img-thumbnail"
                                                        />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="width: 20%"
                                                class="fw-bold"
                                            >
                                                Pilihan E
                                            </td>
                                            <td>
                                                <Editor
                                                    api-key="bvu9lokg76dk4mx0sipczxrdbcmps5ll9px9olsfpqjywybo"
                                                    v-model="form.option_5"
                                                    :init="{
                                                        height: 130,
                                                        menubar: false,
                                                        plugins:
                                                            'lists link image emoticons',
                                                        toolbar:
                                                            'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                    }"
                                                />
                                                <div class="mt-2">
                                                    <label class="form-label"
                                                        >Gambar Pilihan E
                                                        (Optional)</label
                                                    >
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        @change="
                                                            form.option_5_image =
                                                                $event.target.files[0]
                                                        "
                                                        accept="image/*"
                                                    />
                                                    <div
                                                        v-if="
                                                            question.option_5_image
                                                        "
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="`/storage/${question.option_5_image}`"
                                                            alt="Option 5 Image"
                                                            style="
                                                                max-width: 200px;
                                                            "
                                                            class="img-thumbnail"
                                                        />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="width: 20%"
                                                class="fw-bold"
                                            >
                                                Jawaban Benar
                                            </td>
                                            <td>
                                                <select
                                                    class="form-control"
                                                    v-model="form.answer"
                                                >
                                                    <option value="1">A</option>
                                                    <option value="2">B</option>
                                                    <option value="3">C</option>
                                                    <option value="4">D</option>
                                                    <option value="5">E</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-md btn-primary border-0 shadow me-2"
                            >
                                Simpan
                            </button>
                            <button
                                type="reset"
                                class="btn btn-md btn-warning border-0 shadow"
                            >
                                Reset
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//import layout
import LayoutAdmin from "../../../Layouts/Admin.vue";

//import Heade and Link from Inertia
import { Head, Link, router } from "@inertiajs/vue3";

//import reactive from vue
import { reactive } from "vue";

//import sweet alert2
import Swal from "sweetalert2";

//import tinyMCE
import Editor from "@tinymce/tinymce-vue";

export default {
    //layout
    layout: LayoutAdmin,

    //register components
    components: {
        Head,
        Link,
        Editor,
    },

    //props
    props: {
        errors: Object,
        exam: Object,
        question: Object,
    },

    //inisialisasi composition API
    setup(props) {
        //define form with reactive
        const form = reactive({
            question: props.question.question,
            question_image: null,
            option_1: props.question.option_1,
            option_1_image: null,
            option_2: props.question.option_2,
            option_2_image: null,
            option_3: props.question.option_3,
            option_3_image: null,
            option_4: props.question.option_4,
            option_4_image: null,
            option_5: props.question.option_5,
            option_5_image: null,
            answer: props.question.answer,
        });

        //method "submit"
        const submit = () => {
            //send data to server
            router.put(
                `/admin/exams/${props.exam.id}/questions/${props.question.id}/update`,
                {
                    //data
                    question: form.question,
                    question_image: form.question_image,
                    option_1: form.option_1,
                    option_1_image: form.option_1_image,
                    option_2: form.option_2,
                    option_2_image: form.option_2_image,
                    option_3: form.option_3,
                    option_3_image: form.option_3_image,
                    option_4: form.option_4,
                    option_4_image: form.option_4_image,
                    option_5: form.option_5,
                    option_5_image: form.option_5_image,
                    answer: form.answer,
                },
                {
                    forceFormData: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: "Success!",
                            text: "Soal Ujian Berhasil Dipdate!.",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    },
                    onError: (errors) => {
                        console.error("Validation errors:", errors);
                    },
                }
            );
        };

        //return
        return {
            form,
            submit,
        };
    },
};
</script>

<style></style>
