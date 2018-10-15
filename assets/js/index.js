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
import Category from './Components/Category/Category';
import CategoryPanel from './Components/Category/Panel/CategoryPanel';
import AddCategory from './Components/Category/Panel/Add/AddCategory';
import UserPanel from './Components/User/Panel/UserPanel';
import User from './Components/User/User';
import UserBan from './Components/User/Panel/UserBan';
import EditCategory from './Components/Category/Panel/Edit/EditCategory';
import './index.css';
import {SnackbarProvider} from 'notistack';
import {withSnackbar} from 'notistack';
import {Provider} from 'react-redux';
import {store} from './store';
import axios from './axios';
import {loginUser} from './actions/user-action';
import Paper from "@material-ui/core/Paper/Paper";
import CircularProgress from "@material-ui/core/CircularProgress/CircularProgress";
import { syncHistoryWithStore, routerReducer } from 'react-router-redux'
import {BrowserRouter} from 'react-router-dom';


const PublicRoute = withSnackbar(({component: Component, ...rest}) => (
    <Route {...rest} render={(props) => (
        store.getState().user.length === 0
            ? <Component {...props} />
            : <Redirect to='/'/>
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
            open: false,
            loading: true,
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
        axios.post('/api/v1/seciurity', {}, {
            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
        }).then((response) => {
            store.dispatch(loginUser(response.data));
            this.setState({
                loading: false
            })
        }).catch((e) => {
            this.setState({
                loading: false
            })
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
        let indexView;
        if (this.state.loading === false) {
            indexView = <React.Fragment>

                <Switch location={location}>
                    <PublicRoute path='/login' component={Login}/>
                    <PublicRoute path='/registration' component={Registration}/>
                    <PublicRoute path='/user/potwierdz-konto' component={YouMustConfirm}/>
                    <PublicRoute path='/user/token/:token' component={ConfirmUser}/>
                    <PrivateRoute path="/user/ban" component={UserBan}/>
                    <Route path='/category' component={Category}/>
                    <Route path='/user' component={User}/>
                    <PrivateRoute path="/wyloguj" component={Logout}/>
                    <PrivateRoute path="/panel/kategorie/dodaj" component={AddCategory}/>
                    <PrivateRoute path="/panel/user" component={UserPanel}/>
                    <PrivateRoute path="/panel/kategorie" component={CategoryPanel}/>
                    <PrivateRoute path="/panel/edytuj/kategorie/:id" component={EditCategory}/>
                    <Route render={() => <div>Not Found</div>}/>
                </Switch>
            </React.Fragment>
        } else {
            indexView = <Paper>
                <CircularProgress color="secondary"/>
            </Paper>
        }
        return (
            <React.Fragment>
                <Router>
                    <Route
                        render={({location}) => (
                            <SnackbarProvider maxSnack={3}>
                                <div>
                                    <Header handleMenuOpen={this.handleMenuOpen}/>
                                    <DrawerComponent open={this.state.open} handleMenuClose={this.handleMenuClose}/>
                                    {indexView}
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