import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, NavLink, Route, Switch} from 'react-router-dom';
import Login from './Components/Login/Login';
import Registration from './Components/Registration/Registration';
import Header from './Components/Header/Header';
import Footer from './Components/Footer/Footer';
import './index.css';
import {SnackbarProvider} from 'notistack';
import {Provider} from 'react-redux';
import {store} from './store';

class Index extends React.Component {
    render() {
        const styles = {
            root: {
                flexGrow: 1,
            },
            grow: {
                flexGrow: 1,
            },
            menuButton: {
                marginLeft: -12,
                marginRight: 20,
            },
        };

        return (
            <Router>
                <Route
                    render={({location}) => (
                        <SnackbarProvider maxSnack={3}>
                            <div>
                                <Header/>
                                <Switch location={location}>
                                    <Route path="/login" component={Login}/>
                                    <Route path="/registration" component={Registration}/>
                                    <Route render={() => <div>Not Found</div>}/>
                                </Switch>
                                <Footer/>
                            </div>
                        </SnackbarProvider>
                    )}>
                </Route>
            </Router>

        );
    }
}

ReactDOM.render(<Provider store={store}><Index/></Provider>, document.getElementById('root'));