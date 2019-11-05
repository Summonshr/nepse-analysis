import React from 'react';
import axios from 'axios';
import {companies} from '../../config/urls';
import _ from 'lodash';
import History from './charts/history';
import Dividend from './table/dividend';

class Single extends React.Component {

    state = {
        company: false
    }

    constructor(props) {
        super(props)
        this.componentDidMount = this.componentDidMount.bind(this);
    }

    componentDidMount() {
        axios.get(companies + this.props.params.symbol).then(response => this.setState({company: response.data.companies}))
    }

    render() {
        if (!this.state.company) {
            return 'No company yet';
        }
        const company = this.state.company;
        return <div className="w-full flex flex-wrap">
            <div className="w-1/3">
                <h2>{company.name}</h2>
                <p><i className="fa ca-code"></i>Code: {company.code}</p>
                <p><i className={'fa ca-type-' + _.kebabCase(company.type)}></i>Type: {company.type}</p>
                {_.map(company.profile, (profile, key) => <p key={key}><i
                    className={'fa ca-' + _.kebabCase(key)}><span className='text-grey-darker'>{key}: </span>
                    <span className="text-grey-darkest font-bold" dangerouslySetInnerHTML={{__html: profile}}></span>
                </i></p>)}
            </div>
            <div className="w-2/3">
                <History companyCode={company.code}/>
                <Dividend companyCode={company.code}/>
            </div>
        </div>;
    }
}

export default (props) => <Single {...props.match}/>