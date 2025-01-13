<template>

    <Head>
        <title>Detail Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/reports" class="btn btn-md btn-primary border-0 shadow mb-3" type="button">
                <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
                </Link>
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h5><i class="fa fa-stopwatch"></i> Detail Ujian</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <tbody>
                                    <tr>
                                        <td style="width:30%" class="fw-bold">Nama Ujian</td>
                                        <td>{{ examResult.exam.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Mata Pelajaran</td>
                                        <td>{{ examResult.exam.lesson.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kelas</td>
                                        <td>{{ examResult.exam.classroom.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Sesi</td>
                                        <td>{{ examResult.exam_session.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Durasi</td>
                                        <td>{{ examResult.exam.duration }} menit</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nama Siswa</td>
                                        <td>{{ examResult.student.name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Grade</td>
                                        <td>{{ examResult.grade }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-question-circle"></i> Evaluasi Siswa</h5>
                        <hr>
                        <button class="btn btn-success mb-3" @click="exportToExcel">
                            <i class="fa fa-file-excel me-2"></i> Export to Excel
                        </button>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th class="border-0 rounded-start" style="width:5%">No.</th>
                                        <th class="border-0">Pertanyaan</th>
                                        <th class="border-0">Jawaban Siswa</th>
                                        <th class="border-0">Jawaban Benar</th>
                                    </tr>
                                </thead>
                                <div class="mt-2"></div>
                                <tbody>
                                    <tr v-for="(question, index) in questionsWithAnswers" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td v-html="question.text"></td>
                                        <td
                                            :class="{ 'text-success': question.is_correct === 'Y', 'text-danger': question.is_correct !== 'Y' }">
                                            <ol type="A" style="list-style-type: upper-alpha;">
                                                <span v-html="question.student_answer"></span>
                                            </ol>
                                        </td>
                                        <td class="text-success">
                                            <ol type="A" style="list-style-type: upper-alpha;">
                                                <span v-html="question.correct_answer"></span>
                                            </ol>
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
import { Head, Link } from '@inertiajs/vue3';
import LayoutAdmin from '../../../Layouts/Admin.vue';
import * as XLSX from 'xlsx';

export default {
    layout: LayoutAdmin,
    components: {
        Head,
        Link,
    },
    props: {
        examResult: Object,
        questionsWithAnswers: Array,
    },

    methods: {
        exportToExcel() {
            const ws = XLSX.utils.json_to_sheet(this.questionsWithAnswers.map((question, index) => ({
                No: index + 1,
                Pertanyaan: question.text.replace(/(<([^>]+)>)/gi, '') + "\n",
                'Jawaban Siswa': question.student_answer.replace(/(<([^>]+)>)/gi, ''),
                'Jawaban Benar': question.correct_answer.replace(/(<([^>]+)>)/gi, ''),
                'Benar/Salah': question.is_correct === 'Y' ? 'Benar' : 'Salah'
            })));
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Evaluasi Siswa');
            const fileName = `evaluasi_siswa_${this.examResult.student.name.replace(/\s+/g, '_')}.xlsx`;
            XLSX.writeFile(wb, fileName);
        }
    }
};
</script>

<style></style>
