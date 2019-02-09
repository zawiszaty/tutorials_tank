import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../axios';
import {withSnackbar} from 'notistack';
import Avatar from "@material-ui/core/Avatar/Avatar";
import SupervisedUserCircle from '@material-ui/icons/SupervisedUserCircle';
import Typography from "@material-ui/core/Typography/Typography";

const styles = theme => ({
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            minWidth: 500 ,
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
});

class YouMustConfirm extends React.Component {
    constructor(props) {
        super(props);
        const {onPresentSnackbar} = this.props;
        this.state = {
            onPresentSnackbar: onPresentSnackbar
        };
    }

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        <Avatar className={classes.avatar}>
                            <SupervisedUserCircle/>
                        </Avatar>
                        <Typography variant="title" gutterBottom align="center">Przed zalogowaniem potwierdz swoje
                            konto</Typography>
                        <Typography gutterBottom>
                            {`
        Zaloguj sie na email który podałeś podczas rejestracji i potwierdz swoje konto.
        `}
                        </Typography>
                    </Paper>
                </main>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(YouMustConfirm));