import React, {Component} from 'react';
import Header from "./components/Header/Header";
import './app.css';
import FooterComponent from "./components/FooterComponent/FooterComponent";
import {BrowserRouter as Router, Route, Link, Redirect} from "react-router-dom";
import Login from "./containers/Login/Login";
import Registration from "./containers/Registtration/Registration";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import {NotLoggedRoute} from "./components/Routes/NotLoggedRoute";
import CheckSecurity from "./components/Security/CheckSecurity";
import {login} from "./actions/user";
import {connect} from "react-redux";
import withStyles from "./components/Login/LoginForm";
import ConfirmUser from "./containers/ConfirmUser/ConfirmUser";
import {LoggedRoute} from "./components/Routes/LoggedRoute";
import Category from "./components/Category/Category";
import CategoryContainer from "./containers/Category/CategoryContainer";
import UserPanel from "./containers/UserPanel/UserPanel";
import UserListContainer from "./containers/UserPanel/UserListContainer";

const Index = () => <h2>Home</h2>;

class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: true
        }
    }

    loaded = () => {
        this.setState({
            loading: false
        });
    };

    render() {
        return (
            <Router>
                <React.Fragment>
                    <Header/>
                    {this.state.loading === true && <CheckSecurity loaded={this.loaded}/>}
                    {this.state.loading !== true &&
                    <React.Fragment>
                        <Route path="/" exact component={Index}/>
                        <NotLoggedRoute path="/zaloguj" component={Login}/>
                        <NotLoggedRoute path="/zarejestruj" component={Registration}/>
                        <NotLoggedRoute path="/potwierdz/konto/:token" component={ConfirmUser}/>
                        <LoggedRoute path="/panel/uzytkownika" component={UserPanel}/>
                        <Route path="/kategorie" component={CategoryContainer}/>
                        <Route path="/uzytkownicy" component={UserListContainer}/>
                    </React.Fragment>
                    }
                    <FooterComponent/>
                    <ToastContainer/>
                </React.Fragment>
            </Router>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(App);
