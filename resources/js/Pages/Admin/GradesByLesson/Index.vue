<template>
    <Head>
        <title>Nilai Siswa per Mata Pelajaran - Aplikasi Ujian Online</title>
    </Head>

    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5>
                            <i class="fa fa-book-open"></i> Nilai Siswa per Mata
                            Pelajaran
                        </h5>
                        <hr />
                        <p class="text-muted">
                            Pilih mata pelajaran di bawah ini untuk melihat
                            nilai siswa dari semua ujian pada mata pelajaran
                            tersebut. Data ini dapat digunakan untuk input nilai
                            rapor siswa.
                        </p>

                        <div
                            v-if="lessons.length === 0"
                            class="alert alert-warning"
                        >
                            <i class="fa fa-exclamation-triangle"></i> Belum ada
                            mata pelajaran yang tersedia.
                        </div>

                        <div v-else class="row mt-4">
                            <div
                                class="col-md-4 col-sm-6 mb-4"
                                v-for="lesson in lessons"
                                :key="lesson.id"
                            >
                                <div
                                    class="card border-0 shadow-sm h-100 lesson-card"
                                >
                                    <div class="card-body d-flex flex-column">
                                        <div class="mb-3">
                                            <i
                                                class="fa fa-book fa-3x text-primary opacity-25"
                                            ></i>
                                        </div>
                                        <h5 class="card-title mb-3">
                                            {{ lesson.title }}
                                        </h5>
                                        <div class="mt-auto">
                                            <a
                                                :href="`/admin/grades-by-lesson/${lesson.id}`"
                                                class="btn btn-primary btn-sm w-100"
                                            >
                                                <i class="fa fa-eye"></i> Lihat
                                                Nilai
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//import layout Admin
import LayoutAdmin from "../../../Layouts/Admin.vue";

//import Head from Inertia
import { Head } from "@inertiajs/vue3";

export default {
    //layout
    layout: LayoutAdmin,

    //register components
    components: {
        Head,
    },

    //props
    props: {
        lessons: {
            type: Array,
            required: true,
        },
    },
};
</script>

<style scoped>
.lesson-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.lesson-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
