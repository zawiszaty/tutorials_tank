import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, NavLink, Route, Switch} from 'react-router-dom';
import Dashboard from './Components/Dashboard';
import AnotherPage from './Components/AnotherPage';
import { withStyles } from '@material-ui/core/styles';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import Button from '@material-ui/core/Button';
import IconButton from '@material-ui/core/IconButton';
import MenuIcon from '@material-ui/icons/Menu';

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
            <BrowserRouter>
                <div>
                    <AppBar position="static">
                        <Toolbar>
                            <IconButton color="inherit" aria-label="Menu">
                                <MenuIcon />
                            </IconButton>
                            <Typography variant="title" color="inherit">
                                News
                            </Typography>
                            <Button color="inherit">Login</Button>
                        </Toolbar>
                    </AppBar>
                    <ul>
                        <NavLink to="/another-page">Another Page</NavLink>
                        <NavLink to="/another-page/2">Another Page2</NavLink>
                    </ul>
                    <Switch>
                        <Route path="/another-page" component={AnotherPage}>
                            <Route path="/2" component={Dashboard}/>
                        </Route>
                    </Switch>
                </div>
            </BrowserRouter>
        );
    }
}

ReactDOM.render(<Index/>, document.getElementById('root'));