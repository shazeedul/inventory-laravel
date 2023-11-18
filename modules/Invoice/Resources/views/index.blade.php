<x-app-layout>
    <x-card>
        <x-slot name="actions">
            <a href="{{ route(config('theme.rprefix') . '.create') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-plus-circle"></i>&nbsp;@localize('Add New Invoice')</a>
        </x-slot>

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>
    <div id="page-axios-data" data-table-id="#invoice-table"></div>
    @push('js')
        <script>
            function showApproveAlert(route) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to approve this item.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(route);
                        axios.post(route, {
                                _token: '{{ csrf_token() }}',
                                status: 1
                            }).then(function(response) {
                                if (response.data.success) {
                                    Swal.fire(
                                        'Approved!',
                                        'Your item has been approved.',
                                        'success'
                                    )
                                    var table = $("#page-axios-data").data("table-id");
                                    $(table).DataTable().ajax.reload();
                                }
                            })
                            .catch(function(error) {
                                console.log(error);
                            });
                    } else if (result.isDenied) {
                        Swal.fire("Changes are not saved", "", "info");
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
