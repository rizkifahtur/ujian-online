<template>
    <Head>
        <title>
            Ujian Dengan Nomor Soal : {{ page }} - Aplikasi Ujian Online
        </title>
    </Head>
    <div class="row mb-5">
        <div class="col-md-7">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">
                                Soal No.
                                <strong class="fw-bold">{{ page }}</strong>
                            </h5>
                        </div>
                        <div>
                            <VueCountdown
                                :time="duration"
                                @progress="handleChangeDuration"
                                @end="showModalEndTimeExam = true"
                                v-slot="{ hours, minutes, seconds }"
                            >
                                <span class="badge bg-info p-2">
                                    <i class="fa fa-clock"></i> {{ hours }} jam,
                                    {{ minutes }} menit,
                                    {{ seconds }} detik.</span
                                >
                            </VueCountdown>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="question_active !== null">
                        <div>
                            <p v-html="question_active.question.question"></p>
                            <img
                                v-if="question_active.question.question_image"
                                :src="`/storage/${question_active.question.question_image}`"
                                class="img-thumbnail mt-2 mb-3"
                                style="max-width: 100%; max-height: 400px"
                            />
                        </div>

                        <table>
                            <tbody>
                                <tr
                                    v-for="(answer, index) in answer_order"
                                    :key="index"
                                >
                                    <td width="50" style="padding: 10px">
                                        <button
                                            v-if="
                                                answer == question_active.answer
                                            "
                                            class="btn btn-info btn-sm w-100 shdaow"
                                        >
                                            {{ options[index] }}
                                        </button>

                                        <button
                                            v-else
                                            @click.prevent="
                                                submitAnswer(
                                                    question_active.question
                                                        .exam.id,
                                                    question_active.question.id,
                                                    answer
                                                )
                                            "
                                            class="btn btn-outline-info btn-sm w-100 shdaow"
                                        >
                                            {{ options[index] }}
                                        </button>
                                    </td>
                                    <td style="padding: 10px">
                                        <p
                                            v-if="
                                                question_active.question[
                                                    'option_' + answer
                                                ]
                                            "
                                            v-html="
                                                question_active.question[
                                                    'option_' + answer
                                                ]
                                            "
                                        ></p>
                                        <img
                                            v-if="
                                                question_active.question[
                                                    'option_' +
                                                        answer +
                                                        '_image'
                                                ]
                                            "
                                            :src="`/storage/${
                                                question_active.question[
                                                    'option_' +
                                                        answer +
                                                        '_image'
                                                ]
                                            }`"
                                            class="img-thumbnail mt-2"
                                            style="
                                                max-width: 100%;
                                                max-height: 300px;
                                            "
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else>
                        <div class="alert alert-danger border-0 shadow">
                            <i class="fa fa-exclamation-triangle"></i> Soal
                            Tidak Ditemukan!.
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <button
                                v-if="page > 1"
                                @click.prevent="prevPage"
                                type="button"
                                class="btn btn-gray-400 btn-sm btn-block mb-2"
                            >
                                Sebelumnya
                            </button>
                        </div>
                        <div class="text-end">
                            <button
                                v-if="page < Object.keys(all_questions).length"
                                @click.prevent="nextPage"
                                type="button"
                                class="btn btn-gray-400 btn-sm"
                            >
                                Selanjutnya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow">
                <div class="card-header text-center">
                    <div class="badge bg-success p-2">
                        {{ question_answered }} dikerjakan
                    </div>
                </div>
                <div class="card-body" style="height: 330px; overflow-y: auto">
                    <div
                        v-for="(question, index) in all_questions"
                        :key="index"
                    >
                        <div width="20%" style="width: 20%; float: left">
                            <div style="padding: 5px">
                                <button
                                    @click.prevent="clickQuestion(index)"
                                    v-if="index + 1 == page"
                                    class="btn btn-gray-400 btn-sm w-100"
                                >
                                    {{ index + 1 }}
                                </button>

                                <button
                                    @click.prevent="clickQuestion(index)"
                                    v-if="
                                        index + 1 != page &&
                                        question.answer == 0
                                    "
                                    class="btn btn-outline-info btn-sm w-100"
                                >
                                    {{ index + 1 }}
                                </button>

                                <button
                                    @click.prevent="clickQuestion(index)"
                                    v-if="
                                        index + 1 != page &&
                                        question.answer != 0
                                    "
                                    class="btn btn-info btn-sm w-100"
                                >
                                    {{ index + 1 }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button
                        @click="showModalEndExam = true"
                        class="btn btn-danger btn-md border-0 shadow w-100"
                    >
                        Akhiri Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal akhiri ujian -->
    <div
        v-if="showModalEndExam"
        class="modal fade"
        :class="{ show: showModalEndExam }"
        tabindex="-1"
        aria-hidden="true"
        style="display: block"
        role="dialog"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Akhiri Ujian ?</h5>
                </div>
                <div class="modal-body">
                    Setelah mengakhiri ujian, Anda tidak dapat kembali ke ujian
                    ini lagi. Yakin akan mengakhiri ujian?
                </div>
                <div class="modal-footer">
                    <button
                        @click.prevent="endExam"
                        type="button"
                        class="btn btn-primary"
                    >
                        Ya, Akhiri
                    </button>
                    <button
                        @click.prevent="showModalEndExam = false"
                        type="button"
                        class="btn btn-secondary"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal waktu ujian berakhir -->
    <div
        v-if="showModalEndTimeExam"
        class="modal fade"
        :class="{ show: showModalEndTimeExam }"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-hidden="true"
        style="display: block"
        role="dialog"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Waktu Habis !</h5>
                </div>
                <div class="modal-body">
                    Waktu ujian sudah berakhir!. Klik
                    <strong class="fw-bold">Ya</strong> untuk mengakhiri ujian.
                </div>
                <div class="modal-footer">
                    <button
                        @click.prevent="endExam"
                        type="button"
                        class="btn btn-primary"
                    >
                        Ya
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//import layout student
import LayoutStudent from "../../../Layouts/Student.vue";

//import Head and Link from Inertia
import { Head, Link, router } from "@inertiajs/vue3";

//import ref
import { ref, onMounted, onUnmounted } from "vue";

//import VueCountdown
import VueCountdown from "@chenfengyuan/vue-countdown";

//import axios
import axios from "axios";

//import sweet alert2
import Swal from "sweetalert2";

export default {
    //layout
    layout: LayoutStudent,

    //register components
    components: {
        Head,
        Link,
        VueCountdown,
    },

    //props
    props: {
        id: Number,
        page: Number,
        exam_group: Object,
        all_questions: Array,
        question_answered: Number,
        question_active: Object,
        answer_order: Array,
        duration: Object,
    },

    //composition API
    setup(props) {
        //define options for answer
        let options = ["A", "B", "C", "D", "E"];

        //define state counter
        const counter = ref(0);

        //define state duration
        const duration = ref(props.duration.duration);

        //define state for fullscreen monitoring
        const violationCount = ref(0);
        const maxViolations = 3;
        const isMobile =
            /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
                navigator.userAgent
            );
        const isExamEnded = ref(false);

        //request fullscreen
        const requestFullscreen = () => {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen().catch((err) => {
                    console.log("Fullscreen error:", err);
                });
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }

            //lock orientation on mobile
            if (isMobile && screen.orientation && screen.orientation.lock) {
                screen.orientation.lock("portrait").catch((err) => {
                    console.log("Orientation lock error:", err);
                });
            }
        };

        //exit fullscreen handler
        const handleFullscreenChange = () => {
            if (
                !document.fullscreenElement &&
                !document.webkitFullscreenElement &&
                !document.mozFullScreenElement &&
                !document.msFullscreenElement &&
                !isExamEnded.value
            ) {
                violationCount.value++;

                //immediately re-enter fullscreen
                requestFullscreen();

                //show warning AFTER re-entering fullscreen
                setTimeout(() => {
                    Swal.fire({
                        title: "Peringatan!",
                        html: `Anda mencoba keluar dari mode fullscreen!<br>Pelanggaran: ${violationCount.value}/${maxViolations}<br><br>Fullscreen akan tetap terkunci sampai ujian selesai.<br>Ujian akan otomatis diakhiri jika melakukan ${maxViolations} pelanggaran.`,
                        icon: "warning",
                        confirmButtonText: "OK, Lanjutkan Ujian",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then(() => {
                        if (violationCount.value >= maxViolations) {
                            Swal.fire({
                                title: "Ujian Berakhir!",
                                text: `Anda telah melakukan ${maxViolations} pelanggaran. Ujian akan diakhiri.`,
                                icon: "error",
                                showConfirmButton: false,
                                timer: 3000,
                            });
                            setTimeout(() => {
                                endExam();
                            }, 3000);
                        }
                    });
                }, 100);
            }
        };

        //handle visibility change (tab switch)
        const handleVisibilityChange = () => {
            if (document.hidden && !isExamEnded.value) {
                violationCount.value++;

                //ensure fullscreen stays active when returning
                if (!isMobile) {
                    requestFullscreen();
                }

                Swal.fire({
                    title: "Peringatan!",
                    html: `Anda ${
                        isMobile
                            ? "keluar dari aplikasi/berpindah aplikasi"
                            : "berpindah tab/window"
                    }!<br>Pelanggaran: ${
                        violationCount.value
                    }/${maxViolations}<br><br>Ujian akan otomatis diakhiri jika melakukan ${maxViolations} pelanggaran.`,
                    icon: "warning",
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(() => {
                    if (violationCount.value >= maxViolations) {
                        Swal.fire({
                            title: "Ujian Berakhir!",
                            text: `Anda telah melakukan ${maxViolations} pelanggaran. Ujian akan diakhiri.`,
                            icon: "error",
                            showConfirmButton: false,
                            timer: 3000,
                        });
                        setTimeout(() => {
                            endExam();
                        }, 3000);
                    }
                });
            }
        };

        //prevent page unload/navigation
        const handleBeforeUnload = (e) => {
            if (!isExamEnded.value) {
                e.preventDefault();
                e.returnValue = "";
                return "";
            }
        };

        //prevent back button on mobile
        const handlePopState = (e) => {
            if (!isExamEnded.value) {
                e.preventDefault();
                window.history.pushState(null, "", window.location.href);

                Swal.fire({
                    title: "Peringatan!",
                    text: "Anda tidak dapat kembali ke halaman sebelumnya saat ujian berlangsung!",
                    icon: "warning",
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                });
            }
        };

        //prevent window switching (Alt+Tab, etc)
        const handleWindowBlur = () => {
            if (!isExamEnded.value && !isMobile) {
                //refocus window and ensure fullscreen
                window.focus();
                requestFullscreen();

                violationCount.value++;

                setTimeout(() => {
                    Swal.fire({
                        title: "Peringatan!",
                        html: `Anda mencoba berpindah window/aplikasi!<br>Pelanggaran: ${violationCount.value}/${maxViolations}<br><br>Fullscreen akan tetap aktif sampai ujian selesai.<br>Ujian akan otomatis diakhiri jika melakukan ${maxViolations} pelanggaran.`,
                        icon: "warning",
                        confirmButtonText: "OK, Lanjutkan Ujian",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then(() => {
                        window.focus();
                        requestFullscreen();

                        if (violationCount.value >= maxViolations) {
                            Swal.fire({
                                title: "Ujian Berakhir!",
                                text: `Anda telah melakukan ${maxViolations} pelanggaran. Ujian akan diakhiri.`,
                                icon: "error",
                                showConfirmButton: false,
                                timer: 3000,
                            });
                            setTimeout(() => {
                                endExam();
                            }, 3000);
                        }
                    });
                }, 100);
            }
        };

        //prevent right click and shortcuts
        const preventCheating = (e) => {
            //prevent right click
            if (e.type === "contextmenu") {
                e.preventDefault();
                return false;
            }

            //prevent common shortcuts
            if (
                e.ctrlKey &&
                (e.key === "c" ||
                    e.key === "C" ||
                    e.key === "v" ||
                    e.key === "V" ||
                    e.key === "x" ||
                    e.key === "X" ||
                    e.key === "a" ||
                    e.key === "A" ||
                    e.key === "s" ||
                    e.key === "S" ||
                    e.key === "u" ||
                    e.key === "U" ||
                    e.key === "p" ||
                    e.key === "P")
            ) {
                e.preventDefault();
                return false;
            }

            //prevent Alt+Tab, Alt+F4, Windows key, etc
            if (e.altKey || e.metaKey) {
                e.preventDefault();
                return false;
            }

            //prevent Alt+Tab, Alt+F4, Windows key, etc
            if (e.altKey || e.metaKey) {
                e.preventDefault();
                return false;
            }

            //prevent F12, F11, etc
            if ([112, 123].includes(e.keyCode)) {
                e.preventDefault();
                return false;
            }
        };

        //onMounted lifecycle
        onMounted(() => {
            // Prevent back button navigation
            window.history.pushState(null, "", window.location.href);
            window.addEventListener("popstate", handlePopState);
            window.addEventListener("beforeunload", handleBeforeUnload);

            // Intercept all Inertia navigation attempts
            const removeInertiaListener = router.on("before", (event) => {
                if (!isExamEnded.value) {
                    event.preventDefault();

                    Swal.fire({
                        title: "Navigasi Diblokir!",
                        html: "Anda tidak dapat meninggalkan halaman ujian sampai ujian selesai!<br><br>Klik tombol <strong>Akhiri Ujian</strong> jika ingin mengakhiri ujian.",
                        icon: "error",
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                    });

                    return false;
                }
            });

            // Store the remove function to call on unmount
            window.removeInertiaListener = removeInertiaListener;

            //show initial warning
            const warningMessage = isMobile
                ? "Ujian akan dimulai.<br><br><strong>Perhatian (Mobile):</strong><br>- Tetap di halaman ujian<br>- Jangan keluar dari browser<br>- Jangan berpindah aplikasi<br>- Maksimal pelanggaran: 3x<br>- Ujian akan otomatis berakhir jika melanggar"
                : "Ujian akan dimulai dalam mode fullscreen.<br><br><strong>Perhatian:</strong><br>- Jangan keluar dari fullscreen<br>- Jangan berpindah tab/window<br>- Maksimal pelanggaran: 3x<br>- Ujian akan otomatis berakhir jika melanggar";

            Swal.fire({
                title: isMobile ? "Mode Ujian" : "Mode Ujian Fullscreen",
                html: warningMessage,
                icon: "info",
                confirmButtonText: "Mulai Ujian",
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then(() => {
                if (!isMobile) {
                    requestFullscreen();
                }
            });

            //add event listeners
            if (!isMobile) {
                document.addEventListener(
                    "fullscreenchange",
                    handleFullscreenChange
                );
                document.addEventListener(
                    "webkitfullscreenchange",
                    handleFullscreenChange
                );
                document.addEventListener(
                    "mozfullscreenchange",
                    handleFullscreenChange
                );
                document.addEventListener(
                    "MSFullscreenChange",
                    handleFullscreenChange
                );
                window.addEventListener("blur", handleWindowBlur);
            }
            document.addEventListener(
                "visibilitychange",
                handleVisibilityChange
            );
            document.addEventListener("contextmenu", preventCheating);
            document.addEventListener("keydown", preventCheating);
        });

        //onUnmounted lifecycle
        onUnmounted(() => {
            isExamEnded.value = true;

            //remove Inertia navigation listener
            if (window.removeInertiaListener) {
                window.removeInertiaListener();
            }

            //remove event listeners
            window.removeEventListener("popstate", handlePopState);
            window.removeEventListener("beforeunload", handleBeforeUnload);

            if (!isMobile) {
                document.removeEventListener(
                    "fullscreenchange",
                    handleFullscreenChange
                );
                document.removeEventListener(
                    "webkitfullscreenchange",
                    handleFullscreenChange
                );
                document.removeEventListener(
                    "mozfullscreenchange",
                    handleFullscreenChange
                );
                document.removeEventListener(
                    "MSFullscreenChange",
                    handleFullscreenChange
                );
                window.removeEventListener("blur", handleWindowBlur);
            }
            document.removeEventListener(
                "visibilitychange",
                handleVisibilityChange
            );
            document.removeEventListener("contextmenu", preventCheating);
            document.removeEventListener("keydown", preventCheating);

            //exit fullscreen
            if (!isMobile) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });

        //handleChangeDuration
        const handleChangeDuration = () => {
            //decrement duration
            duration.value = duration.value - 1000;

            //increment counter
            counter.value = counter.value + 1;

            //cek jika durasi di atas 0
            if (duration.value > 0) {
                //update duration if 10 seconds
                if (counter.value % 10 == 1) {
                    //update duration
                    axios.put(
                        `/student/exam-duration/update/${props.duration.id}`,
                        {
                            duration: duration.value,
                        }
                    );
                }
            }
        };

        //metohd prevPage
        const prevPage = () => {
            //update duration
            axios.put(`/student/exam-duration/update/${props.duration.id}`, {
                duration: duration.value,
            });

            //redirect to prevPage
            router.get(`/student/exam/${props.id}/${props.page - 1}`);
        };

        //method nextPage
        const nextPage = () => {
            //update duration
            axios.put(`/student/exam-duration/update/${props.duration.id}`, {
                duration: duration.value,
            });

            //redirect to nextPage
            router.get(`/student/exam/${props.id}/${props.page + 1}`);
        };

        //method clickQuestion
        const clickQuestion = (index) => {
            //update duration
            axios.put(`/student/exam-duration/update/${props.duration.id}`, {
                duration: duration.value,
            });

            //redirect to questin
            router.get(`/student/exam/${props.id}/${index + 1}`);
        };

        //method submit answer
        const submitAnswer = (exam_id, question_id, answer) => {
            router.post("/student/exam-answer", {
                exam_id: exam_id,
                exam_session_id: props.exam_group.exam_session.id,
                question_id: question_id,
                answer: answer,
                duration: duration.value,
            });
        };

        //define state modal
        const showModalEndExam = ref(false);
        const showModalEndTimeExam = ref(false);

        //method endExam
        const endExam = () => {
            isExamEnded.value = true;

            //remove Inertia navigation listener before ending
            if (window.removeInertiaListener) {
                window.removeInertiaListener();
            }

            router.post("/student/exam-end", {
                exam_group_id: props.exam_group.id,
                exam_id: props.exam_group.exam.id,
                exam_session_id: props.exam_group.exam_session.id,
            });

            //show success alert
            Swal.fire({
                title: "Success!",
                text: "Ujian Selesai!.",
                icon: "success",
                showConfirmButton: false,
                timer: 4000,
            });
        };

        //return
        return {
            options,
            duration,
            handleChangeDuration,
            prevPage,
            nextPage,
            clickQuestion,
            submitAnswer,
            showModalEndExam,
            showModalEndTimeExam,
            endExam,
        };
    },
};
</script>

<style></style>
