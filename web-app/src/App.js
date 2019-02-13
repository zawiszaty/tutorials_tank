import React, {Component} from 'react';
import Header from "./components/Header/Header";
import './app.css';
import FooterComponent from "./components/FooterComponent/FooterComponent";
import {BrowserRouter as Router, Route} from "react-router-dom";
import Login from "./containers/Login/Login";
import Registration from "./containers/Registtration/Registration";
import {ToastContainer} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import {NotLoggedRoute} from "./components/Routes/NotLoggedRoute";
import CheckSecurity from "./components/Security/CheckSecurity";
import {login} from "./actions/user";
import {connect} from "react-redux";
import ConfirmUser from "./containers/ConfirmUser/ConfirmUser";
import {LoggedRoute} from "./components/Routes/LoggedRoute";
import CategoryContainer from "./containers/Category/CategoryContainer";
import UserPanel from "./containers/UserPanel/UserPanel";
import UserListContainer from "./containers/UserPanel/UserListContainer";
import AddPost from "./containers/Post/Add/AddPost";
import Home from "./containers/Home/Home";
import SinglePost from "./containers/Post/SinglePost/SinglePost";
import EditPost from "./containers/Post/Edit/EditPost";
import PostListContainer from "./containers/Post/PostContainer/PostListContainer";
import Messanger from "./containers/Messanger/Messanger";
import Notification from "./components/Notification/Notification";

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
                        <Route path="/" exact component={Home}/>
                        <NotLoggedRoute path="/zaloguj" component={Login}/>
                        <NotLoggedRoute path="/zarejestruj" component={Registration}/>
                        <NotLoggedRoute path="/potwierdz/konto/:token" component={ConfirmUser}/>
                        <LoggedRoute path="/panel/uzytkownika" component={UserPanel}/>
                        <LoggedRoute path="/dodaj/post" component={AddPost}/>
                        <LoggedRoute path="/edytuj/post/:slug" component={EditPost}/>
                        <LoggedRoute path="/wiadomosci/:id" component={Messanger}/>
                        <Route path="/kategorie" component={CategoryContainer}/>
                        <Route path="/uzytkownicy" component={UserListContainer}/>
                        <Route path="/post/:slug" component={SinglePost}/>
                        <Route path="/posty" component={PostListContainer}/>
                        <Notification/>
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
