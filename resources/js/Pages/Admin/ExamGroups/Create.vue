<template>
    <Head>
        <title>Enrolle Kelas - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link
                    :href="`/admin/exam_sessions/${exam_session.id}`"
                    class="btn btn-md btn-primary border-0 shadow mb-3"
                    type="button"
                    ><i class="fa fa-long-arrow-alt-left me-2"></i>
                    Kembali</Link
                >
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5>
                            <i class="fa fa-user-plus"></i> Enrolle Kelas ke
                            Jadwal Ujian
                        </h5>
                        <hr />

                        <div v-if="is_enrolled" class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> Kelas ini
                            sudah dienroll ke jadwal ujian ini.
                        </div>

                        <form @submit.prevent="submit">
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td
                                                style="width: 30%"
                                                class="fw-bold"
                                            >
                                                Ujian
                                            </td>
                                            <td>{{ exam?.title || "-" }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Pelajaran</td>
                                            <td>
                                                {{ exam?.lesson?.title || "-" }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Kelas</td>
                                            <td>
                                                {{ classroom?.title || "-" }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                Jadwal Ujian
                                            </td>
                                            <td>
                                                {{ exam_session?.title || "-" }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Waktu Mulai</td>
                                            <td>
                                                {{
                                                    exam_session?.start_time
                                                        ? new Date(
                                                              exam_session.start_time
                                                          ).toLocaleString(
                                                              "id-ID"
                                                          )
                                                        : "-"
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                Waktu Selesai
                                            </td>
                                            <td>
                                                {{
                                                    exam_session?.end_time
                                                        ? new Date(
                                                              exam_session.end_time
                                                          ).toLocaleString(
                                                              "id-ID"
                                                          )
                                                        : "-"
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info">
                                <strong>Info:</strong> Dengan menekan tombol
                                "Enrolle Kelas", semua siswa yang ada di kelas
                                <strong>{{ classroom?.title || "ini" }}</strong>
                                akan otomatis terdaftar ke jadwal ujian ini.
                            </div>

                            <button
                                type="submit"
                                class="btn btn-md btn-success border-0 shadow me-2"
                                :disabled="is_enrolled"
                            >
                                <i class="fa fa-check"></i> Enrolle Kelas
                            </button>
                            <Link
                                :href="`/admin/exam_sessions/${exam_session.id}`"
                                class="btn btn-md btn-secondary border-0 shadow"
                            >
                                <i class="fa fa-times"></i> Batal
                            </Link>
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

export default {
    //layout
    layout: LayoutAdmin,

    //register components
    components: {
        Head,
        Link,
    },

    //props
    props: {
        errors: Object,
        exam: Object,
        exam_session: Object,
        classroom: Object,
        is_enrolled: Boolean,
    },

    //inisialisasi composition API
    setup(props) {
        //define form with reactive
        const form = reactive({
            exam_id: props.exam?.id || null,
        });

        //method "submit"
        const submit = () => {
            if (!form.exam_id || !props.exam_session?.id) {
                Swal.fire({
                    title: "Error!",
                    text: "Data tidak lengkap.",
                    icon: "error",
                    showConfirmButton: true,
                });
                return;
            }

            //send data to server
            router.post(
                `/admin/exam_sessions/${props.exam_session.id}/enrolle/store`,
                {
                    //data
                    exam_id: form.exam_id,
                },
                {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: "Success!",
                            text: "Kelas berhasil dienroll ke jadwal ujian!",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    },
                    onError: (errors) => {
                        console.error("Errors:", errors);
                        Swal.fire({
                            title: "Error!",
                            text: errors.message || "Terjadi kesalahan.",
                            icon: "error",
                            showConfirmButton: true,
                        });
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
