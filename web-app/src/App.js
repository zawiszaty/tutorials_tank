import React, {Component} from 'react';
import Header from "./components/Header/Header";
import './app.css';
import FooterComponent from "./components/FooterComponent/FooterComponent";
import {BrowserRouter as Router, Route, Switch} from "react-router-dom";
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
import SingleUser from "./containers/SingleUser/SingleUser";
import UserPostList from "./components/Post/UserPostList";
import Fade from "@material-ui/core/es/Fade/Fade";
import ChangeEmailStatus from "./containers/UserPanel/ChangeEmailStatus";
import PostByCategory from "./components/Post/PostByCategory";
import PasswordRecovery from "./components/PasswordRecovery/PasswordRecovery";
import NotFound from "./components/NotFound";
import Redirect from "react-router-dom/es/Redirect";
import Regulamin from "./containers/Regulamin/Regulamin";

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
                        <Switch>
                            <Route path="/" exact component={Home}/>
                            <Route path="/posty/uzytkownika/:id" exact component={UserPostList}/>
                            <NotLoggedRoute path="/zaloguj" exact component={Login}/>
                            <NotLoggedRoute path="/zarejestruj" exact component={Registration}/>
                            <NotLoggedRoute path="/potwierdz/konto/:token" exact component={ConfirmUser}/>
                            <LoggedRoute path="/panel/uzytkownika" exact component={UserPanel}/>
                            <LoggedRoute path="/dodaj/post" exact component={AddPost}/>
                            <LoggedRoute path="/edytuj/post/:slug" exact component={EditPost}/>
                            <LoggedRoute path="/wiadomosci/:id" exact component={Messanger}/>
                            <Route path="/kategorie" exact component={CategoryContainer}/>
                            <Route path="/uzytkownicy" exact component={UserListContainer}/>
                            <Route path="/post/:slug" exact component={SinglePost}/>
                            <Route path="/uzytkownik/:username" exact component={SingleUser}/>
                            <Route path="/potwierdz/zmiane/emaila/:token" exact component={ChangeEmailStatus}/>
                            <Route path="/kategorie/:id/posty" exact component={PostByCategory}/>
                            <Route path="/posty" exact component={PostListContainer}/>
                            <Route path="/zapomnialem/hasla" exact component={PasswordRecovery}/>
                            <Route path="/regulamin" exact component={Regulamin}/>
                            <Route component={NotFound}/>
                        </Switch>
                        <Notification/>
                    </React.Fragment>
                    }
                    <FooterComponent/>
                    <ToastContainer/>
                </React.Fragment>
            </Router>

        )
            ;
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(App);
