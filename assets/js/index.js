import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, NavLink, Route, Switch} from 'react-router-dom';
import Login from './Components/Login/Login';
import Registration from './Components/Registration/Registration';
import Header from './Components/Header/Header';
import YouMustConfirm from './Components/YouMustConfirm/YouMustConfirm';
import ConfirmUser from './Components/ConfirmUser/ConfirmUser';
import DrawerComponent from './Components/Header/Drawer';
import Footer from './Components/Footer/Footer';
import './index.css';
import {SnackbarProvider} from 'notistack';
import {Provider} from 'react-redux';
import {store} from './store';

class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            open: false
        }

        this.handleMenuOpen = this.handleMenuOpen.bind(this);
        this.handleMenuClose = this.handleMenuClose.bind(this);
    }

    handleMenuOpen() {
        console.log(this.state.open)
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
            <Router>
                <Route
                    render={({location}) => (
                        <SnackbarProvider maxSnack={3}>
                            <div>
                                <Header handleMenuOpen={this.handleMenuOpen}/>
                                <DrawerComponent open={this.state.open} handleMenuClose={this.handleMenuClose}/>
                                <Switch location={location}>
                                    <Route path="/login" component={Login}/>
                                    <Route path="/registration" component={Registration}/>
                                    <Route path="/user/potwierdz-konto" component={YouMustConfirm}/>
                                    <Route path="/user/token/:token" component={ConfirmUser}/>
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