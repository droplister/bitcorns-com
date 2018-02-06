@extends('layouts.app')

@section('title', 'Bitcorn Almanac')

@section('content')
<div class="container">

    <h1 class="display-4 mt-5 mb-2">
        Bitcorn Almanac
    </h1>

    <p class="lead">Definitive guide to <span class="d-none d-sm-inline">BITCORN CROPS</span> harvests (2018-2022).</p>

    <img src="{{ asset('img/farmers-almanac.jpg') }}" alt="Farmers Almanac" class="img-thumbnail img-fluid mt-3 d-none d-sm-inline" />
    <img src="{{ asset('img/farmers-almanac-xs.jpg') }}" alt="Farmers Almanac" class="img-thumbnail img-fluid mt-3 d-inline d-sm-none" />

    <h2 class="mt-5">
        Harvesting Schedule
    </h2>

    <p class="lead">Over the course of four years, CROPS will be harvested sixteen times for a total of 21,000,000 BITCORN.</p>

    <div class="table-responsive">
        <table class="table table-bordered mt-3 mb-4">
            <thead>
                <tr>
                    <th scope="col"><small>Cumulative</small></th>
                    <th scope="col">January</th>
                    <th scope="col">April</th>
                    <th scope="col">July</th>
                    <th scope="col">October</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">2018</th>
                    <td>---</td>
                    <td>2,625,000</td>
                    <td>5,250,000</td>
                    <td>7,875,000</td>
                </tr>
                <tr>
                    <th scope="row">2019</th>
                    <td>10,500,000</td>
                    <td>11,812,500</td>
                    <td>13,125,000</td>
                    <td>14,437,500</td>
                </tr>
                <tr>
                    <th scope="row">2020</th>
                    <td>15,750,000</td>
                    <td>16,537,500</td>
                    <td>17,325,000</td>
                    <td>18,112,500</td>
                </tr>
                <tr>
                    <th scope="row">2021</th>
                    <td>18,900,000</td>
                    <td>19,425,000</td>
                    <td>19,950,000</td>
                    <td>20,475,000</td>
                </tr>
                <tr>
                    <th scope="row">2022</th>
                    <td>21,000,000</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                </tr>
            </tbody>
        </table>
    </div>

    <a name="chart"></a>

    <h2 class="mt-5">
        BITCORN per CROPS
    </h2>

    <p class="lead">Use this tool to plan your farm's harvest:
        @foreach([0.001, 0.01, 0.1, 1, 10] as $n)
            <a href="{{ url(route('almanac', ['crops' => $n])) }}#chart" class="{{ $n == $crops ? 'font-weight-bold' : '' }}">{{ $n  }}&nbsp;CROPS</a>{{ $loop->last ? '.' : ',' }}
        @endforeach
    </p>

    <div class="table-responsive">
        <table class="table table-bordered mt-3 mb-4">
            <thead>
                <tr>
                    <th scope="col"><small>Cumulative</small></th>
                    <th scope="col">January</th>
                    <th scope="col">April</th>
                    <th scope="col">July</th>
                    <th scope="col">October</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">2018</th>
                    <td>---</td>
                    <td>{{ number_format($crops * 26250, 0) }}</td>
                    <td>{{ number_format($crops * 52500, 0) }}</td>
                    <td>{{ number_format($crops * 78750, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2019</th>
                    <td>{{ number_format($crops * 105000, 0) }}</td>
                    <td>{{ number_format($crops * 118125, 0) }}</td>
                    <td>{{ number_format($crops * 131250, 0) }}</td>
                    <td>{{ number_format($crops * 144375, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2020</th>
                    <td>{{ number_format($crops * 157500, 0) }}</td>
                    <td>{{ number_format($crops * 165375, 0) }}</td>
                    <td>{{ number_format($crops * 173250, 0) }}</td>
                    <td>{{ number_format($crops * 181125, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2021</th>
                    <td>{{ number_format($crops * 189000, 0) }}</td>
                    <td>{{ number_format($crops * 194250, 0) }}</td>
                    <td>{{ number_format($crops * 199500, 0) }}</td>
                    <td>{{ number_format($crops * 204750, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2022</th>
                    <td>{{ number_format($crops * 210000, 0) }}</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mt-3 mb-4">
            <thead>
                <tr>
                    <th scope="col"><small>Per&nbsp;Harvest</small></th>
                    <th scope="col">January</th>
                    <th scope="col">April</th>
                    <th scope="col">July</th>
                    <th scope="col">October</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">2018</th>
                    <td>---</td>
                    <td>{{ number_format($crops * 26250, 0) }}</td>
                    <td>{{ number_format($crops * 26250, 0) }}</td>
                    <td>{{ number_format($crops * 26250, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2019</th>
                    <td>{{ number_format($crops * 26250, 0) }}</td>
                    <td>{{ number_format($crops * 13125, 0) }}</td>
                    <td>{{ number_format($crops * 13125, 0) }}</td>
                    <td>{{ number_format($crops * 13125, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2020</th>
                    <td>{{ number_format($crops * 13125, 0) }}</td>
                    <td>{{ number_format($crops * 7875, 0) }}</td>
                    <td>{{ number_format($crops * 7875, 0) }}</td>
                    <td>{{ number_format($crops * 7875, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2021</th>
                    <td>{{ number_format($crops * 7875, 0) }}</td>
                    <td>{{ number_format($crops * 5250, 0) }}</td>
                    <td>{{ number_format($crops * 5250, 0) }}</td>
                    <td>{{ number_format($crops * 5250, 0) }}</td>
                </tr>
                <tr>
                    <th scope="row">2022</th>
                    <td>{{ number_format($crops * 5250, 0) }}</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="text-center text-muted">Farming fewer than 0.01 CROPS is not recommended.</p>

    <br />
    <br />
    <br />

</div>
@endsection