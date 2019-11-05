import React from 'react';
import axios from 'axios';
import {Link} from 'react-router-dom';
import {live, chartData} from '../../config/urls';
import _ from 'lodash';
import Chart from 'chart.js';

class LiveStock extends React.Component {
    state = {
        sort: 'sn',
        sortBy: true,
        stocks: []
    }

    chartDiv = false;

    constructor(props) {
        super(props)
        this.set = this.set.bind(this);
        this.chart = this.chart.bind(this);
        this.displayChart = this.displayChart.bind(this);
        this.chartDiv = false;
    }

    componentDidMount() {
        axios.get(live).then(this.set)
    }

    set(response) {
        this.setState({stocks: response.data.data});
    }

    displayChart(value) {
        var ctx = document.getElementById("today").getContext('2d');
        this.chartDiv = new Chart(ctx, {
            type: 'line',
            data: {
                labels: value.key,
                datasets: [{
                    label: 'LTP of ' + value.name,
                    data: value.data,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0
                    }
                }
            }
        });
    }

    chart(symbol, time) {
        this.chartDiv && this.chartDiv.destroy();
        axios.get(chartData, {params: {symbol, time: time.slice(0, 16)}}).then(response => {
            if (response.data.single.length > 1) {
                this.displayChart({
                    name: response.data.single[0].symbol,
                    key: _.map(response.data.single, 'time'),
                    data: _.map(response.data.single, 'ltp').filter(f => f !== 1)
                })
                return;
            }
            alert('Not enough data for preparing chart.')
        })
    }

    stocks() {
        let stocks = this.state.stocks;
        if (this.state.stock_symbol) {
            stocks = this.state.stocks.filter(stock => stock.symbol.includes(this.state.stock_symbol.toUpperCase()))
        }
        if (this.state.sort) {
            stocks = _.orderBy(stocks, this.state.sort, this.state.sortBy ? 'asc' : 'desc')
        }
        return stocks;
    }

    render() {
        return <div className="container relative" id="live-stock">
            <div className="chart-container max-w-md relative">
                <canvas id="today" className="fixed bg-white hidden pin-r">
                </canvas>
            </div>
            {this.state.stocks.length > 0 && <div className="time flex justify-between">
                <p className="float-left pt-3 mb-0">
                    {this.state.stocks[0].time}
                </p>
                <input type="text" value={this.state.stock_symbol} className='border leading-loose px-1 m-2' placeholder='Search for symbol'
                       onChange={(event) => this.setState({stock_symbol: event.target.value || undefined})}/>
            </div>}
            <table className="table table-hover ">
                <thead>
                <tr>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'sn', sortBy: !this.state.sortBy})}>SN
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'symbol', sortBy: !this.state.sortBy})}>Symbol
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'ltp', sortBy: !this.state.sortBy})}>LTP
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'point_change', sortBy: !this.state.sortBy})}>Point Change
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'open', sortBy: !this.state.sortBy})}>Open
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'high', sortBy: !this.state.sortBy})}>High
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'low', sortBy: !this.state.sortBy})}>Low
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'volume', sortBy: !this.state.sortBy})}>Volume
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sort: 'previous_closing', sortBy: !this.state.sortBy})}>Previous
                        Closing
                    </th>
                </tr>
                </thead>
                <tbody>
                {this.stocks().map(stock => {
                    return <tr key={stock.id}
                               className={stock.point_change < 0 ? 'bg-red text-grey-darkest hover:text-grey-darkest' : 'bg-green text-grey-darkest'}>
                        <td>{stock.sn}</td>
                        <td className="flex justify-between"><Link className="text-grey-darkest"
                                                                   to={'/company/' + stock.symbol}>{stock.symbol}</Link><span
                            onClick={() => this.chart(stock.symbol, stock.time)}
                            className=" cursor-pointer text-grey-darkest hover:text-grey-darkest">G</span></td>
                        <td>{stock.ltp}</td>
                        <td>{stock.point_change}</td>
                        <td>{stock.open}</td>
                        <td>{stock.high}</td>
                        <td>{stock.low}</td>
                        <td>{stock.volume}</td>
                        <td>{stock.previous_closing}</td>
                    </tr>
                })}
                </tbody>
            </table>
        </div>;
    }
}

export default () => <LiveStock/>;