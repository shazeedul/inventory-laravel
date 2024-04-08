<div class="chart-form" id="chart-form">
    <form id="coaAddForm" action="#" method="POST">
        @csrf
        <div id="addCoaFrom">
        </div>
    </form>

    <form id="coaEditForm" action="#" method="POST">
        @csrf
        <div id="editCoaFrom">
        </div>
    </form>

    <form id="coaDeleteForm" action="#" method="POST">
        @csrf
        <div id="deleteCoaFrom">
        </div>
    </form>
</div>
<div id="coa-loader" class="chart-form d-none">
    <div class="d-flex justify-content-center align-items-center">
        <div class="text-center">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-green">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </div>
            <p>{{ localize('loading') }}...</p>
        </div>
    </div>
</div>
