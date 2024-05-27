<x-app-layout>
    @include('account::vouchers.header')
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i>&nbsp;
                @localize('Add Credit Voucher')
            </a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#credit-voucher-table"></div>
    @push('js')
        <script>
            function restoreVoucher(id) {
                Swal.fire({
                    title: 'Confirm Restore',
                    text: 'Are you sure you want to restore this voucher?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post("{{ route('admin.account.transaction.restore', '+id+') }}".replace('+id+', id))
                            .then(response => {
                                if (response.data.success) {
                                    Swal.fire(
                                        'Success',
                                        response.data.message,
                                        'success'
                                    );
                                    $(document).find('#credit-voucher-table').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error',
                                        response.data.message,
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Error',
                                    'Something went wrong!',
                                    'error'
                                );
                            });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
