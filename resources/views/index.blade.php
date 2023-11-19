@extends("layout.app")

@section("content")

    <div class="modal fade bd-example-modal-lg" style="overflow: scroll" id="add-modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #E5E8E8;">
                    <h5 class="modal-title" style="font-weight: bold; font-size: 25px !important; ">Bildirim Ekle</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #F8F9F9;">
                    <form id="announcement_form">

                        <div class="row mt-3 mb-4">
                            <div class="form-group mb-4 col-12">
                                <label class="mb-1" for="name" style="text-decoration: underline;">Bildirim Adı
                                    : </label>
                                <input type="text" name="name" id="name" class="form-control" required>
                                <br>

                                <label class="mb-1" for="city" style="text-decoration: underline;">Mesaj : </label>
                                <textarea name="message" id="message" class="form-control"
                                          style="height: 200px;"></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background-color: #E5E8E8;">
                    <button type="button" onclick="create()" class="btn btn-primary">Kaydet</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="announcement-detail" tabindex="-1" aria-hidden="true"
         style="text-align: start">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Duyuru Detay</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 py-4">
                    <div class="d-flex">
                        <span class="fw-semibold content-title">Mesaj</span>
                        <span class="fw-semibold me-3">:</span>
                        <p id="modal-title"></p>
                    </div>

                    <div id="mail-count" style="display: flex; flex-direction: column;">
                        <span style="display: block;">Gönderilen mail: <span id="count"></span></span>
                        <span style="display: block;">Hataya Düşen: <span id="failed"></span></span>
                        <span style="display: block;">Toplam Kişi: <span id="total"></span></span>
                        <span style="display: block;">Durum: <span id="status"></span></span>
                        <button class="custom-button" id="retryButton" style="display: none;">Hatalıları Tekrar Gönder
                        </button>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary btn-custom"
                            data-bs-dismiss="modal">Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card p-5">
                <table id="announcement_table" class="display nowrap dataTable cell-border" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bildirim Adı</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Bildirim Adı</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer clearfix">
                <button type="button" class="btn btn-info float-left" onclick="openModal()"
                        data-bs-toggle="#add-modal"><i class="fas fa-plus"></i>Bildirim Ekle
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                }
            });
        });


        var table = $('#announcement_table').DataTable({
            order: [
                [0, 'DESC']
            ],
            scrollY: true,
            scrollX: true,
            processing: true,
            serverSide: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Turkish.json'
            },
            ajax: '{!!route('fetch')!!}',
            columns: [
                {data: 'id'},
                {data: 'announcement_name'},
                {data: 'detail'},
            ],
        });

        function openModal() {
            $('#add-modal').modal("toggle");
        }


        function create() {
            var formData = new FormData(document.getElementById('announcement_form'));
            $.ajax({
                type: 'POST',
                url: '{{route('create')}}',
                data: formData,
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}} "},
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı',
                        html: 'Bildirim Oluşturuldu!'
                    }).then(ok => {
                        window.location.reload();
                    });
                    dataTable.ajax.reload();
                },
                error: function (data) {
                    var errors = '';
                    for (datas in data.responseJSON.errors) {
                        errors += data.responseJSON.errors[datas] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Başarısız',
                        html: 'Hata.\n' + errors,
                    });
                }
            });
        }


        function detail(id) {
            $.ajax({
                url: '{{ route('detail') }}',
                data: {id: id},
                method: 'GET',
                success: function (response) {
                    console.log(response)
                    if (response.data) {
                        $('#modal-title').text(response.data.message);
                        var myModal = new bootstrap.Modal(document.getElementById('announcement-detail'));
                        myModal.show();

                        updateMailCount(response.data.id); //anlık olarak job bilgilerini almak için
                        resend_failed_job(response.data.id); // hatalı olan jobları göndermek için

                        mailCountInterval = setInterval(function () {
                            updateMailCount(response.data.id);
                        }, 700); //sürekli olarak bilgilileri yenilemek için
                    }
                }
            });

            // Attach a listener to the modal close event
            $('#announcement-detail').on('hidden.bs.modal', function () {
                // Clear the interval when the modal is closed
                clearInterval(mailCountInterval);
            });
        }

        function updateMailCount(id) {
            $.get("{{ route('mail_count') }}", {id: id}, function (data) {
                $('#count').text(data.success === null ? 0 : data.success);
                $('#total').text(data.total === null ? 0 : data.total);
                $('#failed').text(data.failed === null ? 0 : data.failed);
                $('#status').text(data.status === 1 ? "Bitti" : "Bekleniyor");
                if (data.status === 1 && data.failed > 0) {
                    $('#retryButton').show();
                } else {
                    $('#retryButton').hide();
                }
            });
        }

        function resend_failed_job(id) {
            $('#retryButton').one('click', function (data) {
                $.get("{{ route('resend_failed_job') }}", {id: id}, function (result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı',
                        html: '<br>' + result[1],
                    }).then(ok => {
                        window.location.reload()
                    });
                });
            });
        }
    </script>

@endsection
