import React from 'react';
import { companies, dividendUrl } from '../../../config/urls';
import axios from 'axios';
import { Line } from 'react-chartjs-2';
import _ from 'lodash';
import moment from 'moment';

export default class History extends React.Component {
    state = {
        histories: [],
        year: '',
        criteria: 'closing_price',
        month: '',
        divident: false
    }
    labels = {
        closing_price: 'Closing Price',
        amount: 'Amount',
        min_price: 'Min price',
        max_price: 'Max price',
        no_of_transaction: 'Number of transaction',
        traded_shares: 'Traded shares',
    }

    constructor(props) {
        super(props);
        this.componentDidMount = this.componentDidMount.bind(this);
    }

    componentDidMount() {
        axios.get(companies + this.props.companyCode + '/history').then(response => this.setState({ histories: response.data.histories }))
        axios.get(dividendUrl + this.props.companyCode).then(response => this.setState({ divident: response.data }))
    }

    render() {
        let collection = this.state.histories;
        const chain = _.chain(this.state.histories);
        const years = chain.map(history => history.date.slice(0, 4)).uniq().value();
        let months = _.chain(this.state.histories).map(history => history.date.slice(5, 7)).uniq().sort().value();
        if (this.state.year) {
            collection = _.filter(collection, history => history.date.includes(this.state.year));
            months = _.chain(collection).map(history => history.date.slice(5, 7)).uniq().sort().value();
        }

        if (this.state.month) {
            collection = _.filter(collection, history => history.date.includes('-' + this.state.month + '-'));
        }
        const points = _.map(collection, this.state.criteria);
        const data = {
            labels: _.map(collection, 'date'),
            datasets: [
                {
                    label: this.labels[this.state.criteria],
                    data: points
                },
            ]
        };
        return <div className="w-full">
            <h2 className="w-full">Analysis of share history</h2>
            <div className="w-full flex justify-around">
                <select name="criteria" value={this.state.criteria} onChange={event => this.setState({ criteria: event.target.value })} id="">
                    <option value="closing_price">Closing Price</option>
                    <option value="amount">Amount</option>
                    <option value="no_of_transaction">No of transaction</option>
                    <option value="traded_shares">Traded shares</option>
                    <option value="max_price">Max price</option>
                    <option value="min_price">Min price</option>
                </select>
                <select name="year" value={this.state.year} onChange={event => this.setState({ year: event.target.value })} id="">
                    <option value="">Select a year</option>
                    {years.map(year => <option key={year} value={year}>{year}</option>)}
                </select>
                <select name="month" value={this.state.month} onChange={event => this.setState({ month: event.target.value })} id="">
                    <option value="">Select a month</option>
                    {months.map(month => <option key={month}
                        value={month}>{moment().month(parseInt(month, 10) - 1).format('MMMM')}</option>)}
                </select>
                <span>Avg: {parseInt(_.meanBy(collection, this.state.criteria), 10)}</span>
            </div>
            <Line redraw options={{ elements: { point: { radius: points.length > 30 ? 0 : 2 } } }} data={data} />
        </div>;
    }
}