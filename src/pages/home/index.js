import React from 'react';
import axios from 'axios';
import {Link} from 'react-router-dom';
import {companies} from '../../config/urls';
import _ from 'lodash';
import Check from '../../components/icons/check';
import 'react-input-range/lib/css/index.css';

class Home extends React.Component {

    state = {
        companies: [],
        sort: 'name',
        code: '',
        sortBy: false,
        dividendType: 'cash',
        showOptions: true,
        share: {
            range: {},
            min: 0,
            max: 100,
        }
    };

    constructor(props) {
        super(props);
        this.set = this.set.bind(this)
    }

    componentDidMount() {
        axios.get(companies).then(this.set);
    }

    set(response) {
        this.setState({companies: response.data.companies});
        let range = {
            min: _.min(_.map(response.data.companies, 'latest_share_price')),
            max: _.max(_.map(response.data.companies, 'latest_share_price'))
        };
        this.setState();

        if (_.isEmpty(this.state.share.range)) {
            this.setState({share: {range, ...range}});
        }
    }

    companies() {
        let companies = this.state.companies;

        if (this.state.type) {
            companies = companies.filter(company => company.type === this.state.type);
        }

        if (this.state.highest_month) {
            companies = companies.filter(company => company.highest && company.highest.month === this.state.highest_month);
        }

        if (this.state.lowest_month) {
            companies = companies.filter(company => company.lowest && company.lowest.month === this.state.lowest_month);
        }

        companies = companies.map(company => {
            company.difference = company.lowest && company.latest_share_price - company.lowest.value;
            company.bet = _.values(company.should_invest).filter(Boolean).length;
            return company;
        });

        if (this.state.bet) {
            if (this.state.bet === 'one_star')
                companies = companies.filter(company => _.values(company.should_invest).filter(Boolean).length === 1);
            if (this.state.bet === 'two_star')
                companies = companies.filter(company => _.values(company.should_invest).filter(Boolean).length === 2);
            if (this.state.bet === 'three_star')
                companies = companies.filter(company => _.values(company.should_invest).filter(Boolean).length === 3);
        }

        if (this.state.code) {
            companies = companies.filter(company => company.code && company.code.toLowerCase().includes(this.state.code.toLowerCase()))
        }

        if (this.state.name) {
            companies = companies.filter(company => company.name && company.name.toLowerCase().includes(this.state.name.toLowerCase()))
        }

        if (this.state.share.range) {
            if (this.state.share.range.min) {
                companies = companies.filter(company => company.latest_share_price >= this.state.share.range.min);
            }

            if (this.state.share.range.max) {
                companies = companies.filter(company => company.latest_share_price <= this.state.share.range.max);
            }
        }

        if (this.state.sort) {
            companies = _.orderBy(companies, company => _.get(company, this.state.sort)).filter(company => _.get(company, this.state.sort) !== undefined)
            if (this.state.sortBy) {
                companies = _.reverse(companies);
            }
        }

        companies = companies.filter(company => company.latest_share_price)

        return companies;
    }

    star(company) {
        if (company.investNow) {
            return 'bg-green-dark text-white';
        }

        if (company.superInvest) {
            return 'bg-green text-grey-darker';
        }

        if (company.invest) {
            return 'bg-green-light text-grey-darkest'
        }
        return 'bg-white';
    }

    render() {
        return <div className="w-full">
            {this.state.showOptions && <div>
                <div className="w-full flex flex-wrap justify-between my-4">
                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2"><select className="form-control rounded-none mb-2"
                                                                      value={this.state.type}
                                                                      onChange={event => this.setState({type: event.target.value})}>
                        <option value="">Select type</option>
                        {_.chain(this.state.companies).map(company => company.type).uniq().value().map(type => <option
                            key={type}
                            value={type}>{type}</option>)}
                    </select></div>
                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2">
                        <select className="form-control rounded-none mb-2"
                                value={this.state.highest_month}
                                onChange={event => this.setState({highest_month: event.target.value})}>
                            <option value="">Select Highest Month</option>
                            {_.chain(this.state.companies).map(company => company.highest ? company.highest.month : false).filter(Boolean).uniq().orderBy(month => new Date(month + '-1-01').getMonth() + 1).value().map(month =>
                                <option
                                    key={month}
                                    value={month}
                                >{month}</option>)}
                        </select>
                    </div>
                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2"><select
                        className="form-control rounded-none mb-2"
                        value={this.state.lowest_month}
                        onChange={event => this.setState({lowest_month: event.target.value})}>
                        <option value="">Select Lowest Month</option>
                        {_.chain(this.state.companies).map(company => company.lowest ? company.lowest.month : false).filter(Boolean).uniq().orderBy(month => new Date(month + '-1-01').getMonth() + 1).value().map(month =>
                            <option
                                key={month}
                                value={month}
                            >{month}</option>)}
                    </select></div>
                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2"><select
                        className="form-control rounded-none mb-2"
                        value={this.state.bet}
                        onChange={event => this.setState({bet: event.target.value})}
                    >
                        <option value="">Select Bet type</option>
                        <option value="one_star">One Star</option>
                        <option value="two_star">Two Star</option>
                        <option value="three_star">Three Star</option>
                    </select></div>
                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2"><select className="form-control rounded-none mb-2"
                                                                      onChange={event => this.setState({dividendType: event.target.value})}>
                        <option value="cash">Cash</option>
                        <option value="bonus">Bonus</option>
                        <option value="right">Right</option>
                    </select></div>
                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2">
                        <button
                            className="p-2 text-white border bg-red btn-block"
                            onClick={event => this.setState({
                                highest_month: '',
                                lowest_month: '',
                                bet: '',
                                type: ''
                            })}>Reset
                        </button>
                    </div>
                </div>
                <div className="w-full flex flex-wrap justify-around my-4">
                    <div className="w-full ">Min and max price range</div>
                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2"></div>
                    <select className="form-control rounded-none mb-2" value={this.state.share.range.min}
                            onChange={event => this.setState({
                                share: {
                                    ...this.state.share,
                                    range: {min: event.target.value, max: this.state.share.range.max}
                                }
                            })}>
                        <option value="">Select min share price</option>
                        {_.range(0, Math.round((this.state.share.max + 1000) / 1000) * 1000, 100).map(number => <option
                            key={number}
                            value={number}>{number}</option>)}
                    </select>

                    <div className="w-full md:w-1/2 lg:w-1/4 md:pr-2"></div>
                    <select className="form-control rounded-none mb-2" value={this.state.share.range.max}
                            onChange={event => this.setState({
                                share: {
                                    ...this.state.share,
                                    range: {min: this.state.share.range.min, max: event.target.value}
                                }
                            })}>
                        <option value="">Select max share price</option>
                        {_.range(0, Math.round((this.state.share.max + 1000) / 1000) * 1000, 100).reverse().map(number =>
                            <option
                                key={number}
                                value={number}>{number}</option>)}
                    </select>
                </div>
            </div>}
            <button class="btn btn-primary btn-block rounded-none my-2"
                    onClick={() => this.setState({showOptions: !this.state.showOptions})}>Toggle Options
            </button>
            <table className="table table-bordered mx-auto">
                <thead>
                <tr>
                    <th>
                        <input
                            value={this.state.name}
                            className="border bg-grey-lighter p-2 w-full"
                            onChange={event => this.setState({name: event.target.value})}
                            type="text" placeholder='Name'/>
                    </th>
                    <th class="hidden md:table-cell">
                        <input
                            value={this.state.code}
                            className="border bg-grey-lighter p-2 w-16"
                            onChange={event => this.setState({code: event.target.value})}
                            type="text" placeholder='code'/>
                    </th>
                    <th className="cursor-pointer hidden md:table-cell"
                        onClick={() => this.setState({sortBy: !this.state.sortBy, sort: 'type'})}>Type
                    </th>
                    <th className="cursor-pointer"
                        onClick={() => this.setState({sortBy: !this.state.sortBy, sort: 'latest_share_price'})}>Current
                    </th>
                    <th className="cursor-pointer hidden md:table-cell"
                        title="Highest monthly average"
                        onClick={() => this.setState({sortBy: !this.state.sortBy, sort: 'highest.value'})}>HMA
                    </th>
                    <th className="cursor-pointer hidden md:table-cell"
                        title="Lowest monthly average "
                        onClick={() => this.setState({sortBy: !this.state.sortBy, sort: 'lowest.value'})}>LMA
                    </th>
                    <th className="cursor-pointer hidden sm:table-cell"
                        onClick={() => this.setState({
                            sortBy: !this.state.sortBy,
                            sort: 'avg_dividend.' + this.state.dividendType
                        })}>
                        Dividend
                    </th>
                    <th title="Current - Lowest monthly average" className="cursor-pointer w-32 hidden md:table-cell"
                        onClick={() => this.setState({sortBy: !this.state.sortBy, sort: 'difference'})}>
                        <span>Difference</span>
                    </th>
                    <th className="cursor-pointer w-24"
                        onClick={() => this.setState({sortBy: !this.state.sortBy, sort: 'bet'})}>
                        BET
                    </th>
                </tr>
                </thead>
                <tbody>
                {this.companies().map(company => {
                    return <tr key={company.code}
                               className={this.star(company)}>
                        <td>
                            <Link className="no-underline text-blue-darker"
                                  to={{pathname: '/company/' + company.code}}>{company.name}</Link>
                        </td>
                        <td class="hidden md:table-cell">{company.code}</td>
                        <td class="hidden md:table-cell">{company.type}</td>
                        <td class="">
                            {company.latest_share_price}
                        </td>
                        <td class="hidden md:table-cell">
                            {company.highest && company.highest.month}@{company.highest && company.highest.value}
                        </td>
                        <td class="hidden md:table-cell">
                            {company.lowest && company.lowest.month}@{company.lowest && company.lowest.value}
                        </td>
                        <td class="hidden sm:table-cell">
                            <Link className="no-underline text-blue-darker"
                                  to={{pathname: '/company/' + company.code}}>
                                <span>{company.avg_dividend && company.avg_dividend[this.state.dividendType]}</span>
                            </Link>
                        </td>
                        <td className="w-32 hidden md:table-cell">
                            <span>{Math.ceil(company.difference)}</span>
                        </td>
                        <td className="w-24 text-center">
                            {_.map(company.should_invest, (invest, index) => invest &&
                                <Check key={index} title={index}/>)}
                        </td>
                    </tr>
                })}
                </tbody>
            </table>
        </div>
    }
}

export default props => <Home {...props.match}/>;
