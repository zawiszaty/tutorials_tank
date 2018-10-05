import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, NavLink, Route, Switch, Redirect} from 'react-router-dom';
import Login from './Components/Login/Login';
import Registration from './Components/Registration/Registration';
import Header from './Components/Header/Header';
import YouMustConfirm from './Components/YouMustConfirm/YouMustConfirm';
import ConfirmUser from './Components/ConfirmUser/ConfirmUser';
import DrawerComponent from './Components/Header/Drawer';
import Footer from './Components/Footer/Footer';
import Logout from './Components/Logout/Logout';
import './index.css';
import {SnackbarProvider} from 'notistack';
import {withSnackbar} from 'notistack';
import {Provider} from 'react-redux';
import {store} from './store';
import axios from './axios';
import {loginUser} from './actions/user-action';

const PublicRoute = withSnackbar(({component: Component, ...rest}) => (
    <Route {...rest} render={(props) => (
        store.getState().user.length === 0
            ? <Component {...props} />
            : <Redirect to='/' />
    )}/>
))

const PrivateRoute = withSnackbar(({component: Component, ...rest}) => (
    <Route {...rest} render={(props) => (
        store.getState().user.length !== 0
            ? <Component {...props} />
            : <Redirect to='/'/>
    )}/>
))


class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            open: false
        }

        this.handleMenuOpen = this.handleMenuOpen.bind(this);
        this.handleMenuClose = this.handleMenuClose.bind(this);
        let then = this;

        function handleChange() {
            then.forceUpdate();
        }

        store.subscribe(handleChange);
    }

    componentDidMount() {
        axios.post('api/v1/seciurity', {}, {
            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
        }).then((response) => {
            store.dispatch(loginUser(response.data));
        }).catch((e) => {
        });
    }

    handleMenuOpen() {
        if (this.state.open) {
            this.setState({
                open: false
            })
        } else {
            this.setState({
                open: true
            })
        }
    }

    handleMenuClose() {
        console.log(this.state.open);
        this.setState({
            open: false
        })

    }

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
            <React.Fragment>
                <Router>
                    <Route
                        render={({location}) => (
                            <SnackbarProvider maxSnack={3}>
                                <div>
                                    <Header handleMenuOpen={this.handleMenuOpen}/>
                                    <DrawerComponent open={this.state.open} handleMenuClose={this.handleMenuClose}/>
                                    <Switch location={location}>
                                        <PublicRoute path='/login' component={Login}/>
                                        <PublicRoute path='/registration' component={Registration}/>
                                        <PublicRoute path='/user/potwierdz-konto' component={YouMustConfirm}/>
                                        <PublicRoute path='/user/token/:token' component={ConfirmUser}/>
                                        <PrivateRoute path="/wyloguj" component={Logout}/>
                                        <Route render={() => <div>Not Found</div>}/>
                                    </Switch>
                                    <Footer/>
                                </div>
                            </SnackbarProvider>
                        )}>
                    </Route>
                </Router>
            </React.Fragment>
        );
    }
}

ReactDOM.render(<Provider store={store}><Index/></Provider>, document.getElementById('root'));