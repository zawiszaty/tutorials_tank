import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, NavLink, Route, Switch} from 'react-router-dom';
import CssBaseline from '@material-ui/core/CssBaseline';
import Button from '@material-ui/core/Button';
import Login from './Components/Login/Login';
import Registration from './Components/Registration/Registration';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import AppBar from '@material-ui/core/AppBar';
import {TransitionGroup, CSSTransition} from "react-transition-group";
import './index.css';

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
                        <div>
                            <div className={styles.root}>
                                <AppBar position="static" color="default">
                                    <Toolbar>
                                        <Typography variant="title" color="inherit">
                                            Tutorials Tank
                                        </Typography>
                                        <NavLink to="/login" ><Button color="inherit" className={styles.menuButton}>Zaloguj
                                            się</Button></NavLink>
                                        <NavLink to="/Registration"><Button className={styles.menuButton}
                                            color="inherit">Zarejestruj się</Button></NavLink>
                                    </Toolbar>
                                </AppBar>
                            </div>
                            <TransitionGroup>
                                {/* no different than other usage of
                CSSTransition, just make sure to pass
                `location` to `Switch` so it can match
                the old location as it animates out
            */}
                                <CSSTransition key={location.key} classNames="fade" timeout={300}>
                                    <Switch location={location}>
                                        <Route path="/login" component={Login}>/
                                        </Route>
                                        <Route path="/registration" component={Registration}/>

                                        <Route render={() => <div>Not Found</div>}/>
                                    </Switch>
                                </CSSTransition>
                            </TransitionGroup>
                        </div>
                    )}>
                </Route>
            </Router>

        );
    }
}

ReactDOM.render(<Index/>, document.getElementById('root'));