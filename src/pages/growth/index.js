import React from 'react';
import axios from 'axios';
import {growthGraph} from '../../config/urls';
import {Link} from 'react-router-dom';

export class Graph extends React.Component{

    constructor(props){
        super(props)
        this.state = {graphs:[]}
    }

    componentDidMount(){
        axios.get(growthGraph).then(response=>this.setState({graphs: response.data}))
    }

    render(){

        return <div>
            <table className="table table-bordered">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Gain</th>
                        <th>Loss</th>
                        <th>Graph</th>
                    </tr>
                </thead>
                <tbody>
                    {this.state.graphs.map(graph => <tr key={graph.name}>
                        <td>
                        <Link className="no-underline text-blue-darker"
                                  to={{pathname: '/company/' + graph.code}}>{graph.name}</Link>
                        </td>
                        <td>{graph.count.G}</td>
                        <td>{graph.count.L}</td>
                        <td></td>
                    </tr>) }
                </tbody>
            </table>
        </div>
    }
}

export default (props) => <Graph {...props.match}/>