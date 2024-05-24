<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                onclick="approvedVoucher()">&nbsp;@localize('Approve All Check')</a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#pending_voucher-table"></div>
    @push('js')
        <script>
            $('#selectall').click(function(event) {
                if (this.checked) {
                    // Iterate each checkbox
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            function selectAll() {
                var isChecked = $('#check_all').prop('checked');
                $('input[name="voucher_checkbox[]"]').prop('checked', isChecked);
            }

            function approvedVoucher(e) {
                let voucherId = $('input[name="voucher_checkbox[]"]:checked').map(function() {
                    return this.value;
                }).get();

                if (voucherId.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please Select Voucher',
                    })
                    return false;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to Approve Voucher",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post("{{ route('admin.account.transaction.approve') }}", {
                            voucherId: voucherId
                        }).then((response) => {
                            if (response.data.success) {
                                Swal.fire(
                                    'Approved!',
                                    response.data.message,
                                    'success'
                                )
                                $('#pending_voucher-table').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.data.message,
                                })
                            }
                        }).catch((error) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        });
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>
