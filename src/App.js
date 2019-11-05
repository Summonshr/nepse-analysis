import React, {Component} from 'react';
import 'bootstrap/dist/css/bootstrap.css'
import './all.css';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';
import Home from './pages/home';
import LiveStock from './pages/livestock';
import {SingleCompany} from './pages/company';
import GrowthGraph from './pages/growth';
import PortFolio from './pages/portfolio';

class App extends Component {
    render() {
        return <Router>
            <div className="container mx-auto">
                <Switch>
                    <Route path="/" exact render={PortFolio}/>
                    <Route path="/search" render={Home}/>
                    <Route path="/live-stock" render={LiveStock}/>
                    <Route path="/company/:symbol" render={SingleCompany}/>
                    <Route path="/growth-graph" render={GrowthGraph} />
                </Switch>
            </div>
        </Router>
    }
}

export default App;
