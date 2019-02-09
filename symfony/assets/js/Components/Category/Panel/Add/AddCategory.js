import React from 'react';
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import FormControl from '@material-ui/core/FormControl';
import Input from '@material-ui/core/Input';
import InputLabel from '@material-ui/core/InputLabel';
import LockIcon from '@material-ui/icons/LockOutlined';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import TextField from '@material-ui/core/TextField';
import withStyles from '@material-ui/core/styles/withStyles';
import {withSnackbar} from 'notistack';
import AddCategoryForm from './AddCategoryForm';
import {Route, Redirect} from 'react-router'

const styles = theme => ({
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: 400,
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

class AddCategory extends React.Component {
    constructor(props) {
        super(props);

        const {onPresentSnackbar} = this.props;
        this.state = {
            name: '',
            password: '',
            showPassword: false,
            onPresentSnackbar: onPresentSnackbar,
            redirect: false
        };
        this.redirect = this.redirect.bind(this);
    }

    redirect() {
        this.setState({
            redirect: true
        })
    }

    render() {
        const classes = this.props.classes;
        let view;
        if (this.state.redirect === true) {
            view = <Redirect to="/panel/kategorie"/>
        }
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        <Avatar className={classes.avatar}>
                            <LockIcon/>
                        </Avatar>
                        <Typography variant="headline">Dodaj Kategorie</Typography>
                        <AddCategoryForm redirect={this.redirect}/>
                    </Paper>
                </main>
                {view}
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(AddCategory));