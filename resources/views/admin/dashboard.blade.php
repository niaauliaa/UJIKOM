@extends('template.sidebar') 
@section('content') 

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Selamat Datang!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            customClass: {
            popup: 'small-swal'
        }
        });
    </script>
@endif

<div class="container">
    <link rel="stylesheet" href="{{asset('Assets/css/alert.css')}}">
    <a href="{{route('admin.dashboard')}}"> 
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size: 15px; color: #858585;"> > Dashboard</small>
    <div class="join d-flex align-items-center justify-content-between">
        <div class="content d-flex align-items-center" style="justify-content: space-between;">
            <h5 class="mt-4 mb-2"><b>Dashboard</b></h5>
        </div>
    </div>

    <div class="card p-4" style="width:960px;">
        <div class="row">
            <!--  bar chart -->
            <div class="col-md-6">
                <div class="card-body">
                    <canvas id="barChart" style="width: 100%; height: 380px;"></canvas>
                </div>
            </div>

            <!-- pie chart -->
            <div class="col-md-5">
                <div class="card-body">
                    <canvas id="pieChart" style="width: 80%; height: 330px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const barLabels = @json($barLabels);
    const barValues = @json($barValues);
    const pieLabels = @json($pieLabels);
    const pieValues = @json($pieValues);

    // Bar Chart
    new Chart("barChart", {
        type: "bar",
        data: {
            labels: barLabels,
            datasets: [{
                label: "Jumlah Pembelian",
                backgroundColor: "rgba(26, 157, 250, 0.1)",
                borderColor: "#1a9dfa",
                borderWidth: 1,
                data: barValues
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 20,
                        font: { size: 12 }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 15 } }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                title: {
                    display: true,
                    text: 'Selamat Datang, Administrator!',
                    color: '#000000',
                    align: 'start',
                    font: { size: 20, weight: 15 }
                }
            }
        }
    });

   // Pie Chart
    new Chart("pieChart", {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: [
                    'rgba(58, 125, 68, 0.1)',
                    'rgba(26, 157, 250, 0.1)',
                    'rgba(183, 113, 229, 0.1)',
                    'rgba(184, 33, 50, 0.1)',
                    'rgba(255, 157, 35, 0.1)',
                    'rgba(255, 217, 95, 0.1)'
                ],
                borderColor: ['#3A7D44', '#1a9dfa', '#B771E5', '#B82132','#FF9D23', '#FFD95F'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' },
                title: {
                    display: true,
                    text: 'Persentase Pembelian Produk',
                    color: '#4b4b4b',
                    font: { size: 16 }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const data = context.dataset.data;
                            const total = data.reduce((a, b) => Number(a) + Number(b), 0);
                            const value = Number(context.raw);
                            const percentage = ((value / total) * 100).toFixed(2);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
});
</script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

@endsection
