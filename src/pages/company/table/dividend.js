import React from 'react';
import axios from 'axios';
import {dividendUrl} from '../../../config/urls';
import _ from 'lodash';

class Dividend extends React.Component {
    state = {};

    constructor(props) {
        super(props);
        this.componentDidMount = this.componentDidMount.bind(this);
    }

    componentDidMount() {
        axios.get(dividendUrl + this.props.companyCode).then(response => this.setState({data: response.data}))
    }

    render() {
        if (!this.state.data) {
            return <div>No dividends yet</div>
        }
        return <div className="w-full flex flex-wrap justify-between">
            {_.map(_.groupBy(this.state.data, 'type'), (data, group) => <div className="pl-2 flex-1" key={group}>
                <table
                    className="table table-bordered">
                    <thead>
                    <tr>
                        <th colSpan="2">{_.capitalize(group)}: {Math.ceil(_.meanBy(data, 'dividend'))}% Avg</th>
                    </tr>
                    </thead>
                    <tbody>
                    {data.map(dividend => <tr key={dividend.id}>
                        <td>{dividend.dividend}</td>
                        <td>{dividend.distribution_date}</td>
                    </tr>)}
                    </tbody>
                </table>
            </div>)
            }
        </div>
    }
}

export default Dividend;
