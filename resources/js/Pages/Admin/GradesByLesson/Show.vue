<template>
    <Head>
        <title>Nilai {{ lesson.title }} - Aplikasi Ujian Online</title>
    </Head>

    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Header Card -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-8">
                                <h5 class="mb-2">
                                    <a
                                        href="/admin/grades-by-lesson"
                                        class="text-decoration-none"
                                    >
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                    <i class="fa fa-book-open ms-2"></i> Nilai
                                    Mata Pelajaran: {{ lesson.title }}
                                </h5>
                                <p class="text-muted mb-0">
                                    <i class="fa fa-users"></i> Total
                                    {{ totalStudents }} siswa &nbsp;|&nbsp;
                                    <i class="fa fa-file-alt"></i>
                                    {{ totalExams }} ujian
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a
                                    :href="exportUrl"
                                    class="btn btn-success btn-md shadow"
                                    target="_blank"
                                >
                                    <i class="fa fa-file-excel"></i> Download
                                    Excel
                                </a>
                            </div>
                        </div>

                        <!-- Filter Kelas -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-bold"
                                    >Filter Kelas:</label
                                >
                                <select
                                    class="form-select"
                                    v-model="selectedClassroom"
                                    @change="filterByClassroom"
                                >
                                    <option value="">Semua Kelas</option>
                                    <option
                                        v-for="classroom in classrooms"
                                        :key="classroom.id"
                                        :value="classroom.id"
                                    >
                                        {{ classroom.title }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="gradesByExam.length === 0"
                    class="alert alert-warning"
                >
                    <i class="fa fa-exclamation-triangle"></i> Belum ada data
                    nilai untuk mata pelajaran ini.
                </div>

                <!-- Grades by Exam -->
                <div
                    v-for="(item, index) in gradesByExam"
                    :key="index"
                    class="card border-0 shadow mb-4"
                >
                    <div class="card-body">
                        <!-- Exam Header -->
                        <div
                            class="d-flex justify-content-between align-items-start mb-3"
                        >
                            <div>
                                <h5 class="mb-1">
                                    <i class="fa fa-file-alt text-primary"></i>
                                    {{ item.exam.title }}
                                </h5>
                                <p class="text-muted mb-0">
                                    <i class="fa fa-school"></i> Kelas:
                                    {{ item.exam.classroom.title }}
                                    &nbsp;|&nbsp; <i class="fa fa-users"></i>
                                    {{ item.total_students }} siswa mengikuti
                                </p>
                            </div>
                            <div class="text-end">
                                <div class="badge bg-success mb-1">
                                    Tertinggi: {{ item.highest }}
                                </div>
                                <br />
                                <div class="badge bg-warning text-dark mb-1">
                                    Rata-rata: {{ item.average }}
                                </div>
                                <br />
                                <div class="badge bg-danger">
                                    Terendah: {{ item.lowest }}
                                </div>
                            </div>
                        </div>

                        <hr />

                        <!-- Grades Table -->
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-hover mb-0"
                            >
                                <thead class="table-light">
                                    <tr>
                                        <th
                                            style="width: 5%"
                                            class="text-center"
                                        >
                                            No.
                                        </th>
                                        <th style="width: 15%">NISN</th>
                                        <th style="width: 30%">Nama Siswa</th>
                                        <th
                                            style="width: 15%"
                                            class="text-center"
                                        >
                                            Kelas
                                        </th>
                                        <th
                                            style="width: 15%"
                                            class="text-center"
                                        >
                                            Jawaban Benar
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="text-center"
                                        >
                                            Nilai
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="text-center"
                                        >
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            grade, gradeIndex
                                        ) in item.grades"
                                        :key="grade.id"
                                    >
                                        <td class="text-center fw-bold">
                                            {{ gradeIndex + 1 }}
                                        </td>
                                        <td>{{ grade.student.nisn }}</td>
                                        <td>{{ grade.student.name }}</td>
                                        <td class="text-center">
                                            {{ grade.exam.classroom.title }}
                                        </td>
                                        <td class="text-center">
                                            {{ grade.total_correct }}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                :class="
                                                    getGradeClass(grade.grade)
                                                "
                                                class="badge"
                                            >
                                                {{ grade.grade }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a
                                                :href="`/admin/reports/show/${grade.id}`"
                                                class="btn btn-sm btn-info text-white"
                                                title="Lihat Detail"
                                            >
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
import { Head, router } from "@inertiajs/vue3";

export default {
    //layout
    layout: LayoutAdmin,

    //register components
    components: {
        Head,
    },

    //props
    props: {
        lesson: {
            type: Object,
            required: true,
        },
        gradesByExam: {
            type: Array,
            required: true,
        },
        totalStudents: {
            type: Number,
            required: true,
        },
        totalExams: {
            type: Number,
            required: true,
        },
        classrooms: {
            type: Array,
            required: true,
        },
        selectedClassroomId: {
            type: [String, Number],
            default: null,
        },
    },

    //data
    data() {
        return {
            selectedClassroom: this.selectedClassroomId || "",
        };
    },

    //computed
    computed: {
        exportUrl() {
            let url = `/admin/grades-by-lesson/${this.lesson.id}/export`;
            if (this.selectedClassroom) {
                url += `?classroom_id=${this.selectedClassroom}`;
            }
            return url;
        },
    },

    //methods
    methods: {
        getGradeClass(grade) {
            if (grade >= 80) return "bg-success";
            if (grade >= 70) return "bg-primary";
            if (grade >= 60) return "bg-warning text-dark";
            return "bg-danger";
        },
        filterByClassroom() {
            let url = `/admin/grades-by-lesson/${this.lesson.id}`;
            if (this.selectedClassroom) {
                url += `?classroom_id=${this.selectedClassroom}`;
            }
            router.get(url);
        },
    },
};
</script>

<style scoped>
.badge {
    font-size: 0.95rem;
    padding: 0.4rem 0.6rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
