import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Paper from "@material-ui/core/Paper";
import CircularProgress from "@material-ui/core/CircularProgress";
import CssBaseline from "@material-ui/core/CssBaseline";
import {withStyles} from "@material-ui/core";
import axios from "../../axios/axios";
import Typography from "@material-ui/core/Typography";
import {login} from "../../actions/user";
import {connect} from "react-redux";
import Category from "../../components/Category/Category";

const styles = theme => ({
    progress: {
        margin: theme.spacing.unit * 2,
    },
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: '80%',
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
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

class CategoryContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: true,
            categories: {},
            error: false,
        }
    }

    getAllCategory = () => {
        axios.get('/api/v1/category')
            .then((response) => {
                const categories = response.data;
                const loading = false;
                this.setState({
                    categories,
                    loading
                });
            })
            .catch((e) => {
                    this.setState({
                        loading: false
                    });
            })
    };

    componentDidMount = () => {
        this.getAllCategory();
    };

    render() {
        const {classes} = this.props;

        if (this.state.loading === false) {
            return (
                <React.Fragment>
                    <Category/>
                    {/*{*/}
                        {/*this.props.user.length !== 0 &&*/}
                        {/*<div>404</div>*/}
                    {/*}*/}
                </React.Fragment>
            );
        }

        if (this.state.error === true) {
            return (
                <main className={classes.main}>
                    <CssBaseline/>
                    <Paper className={classes.paper}>
                        <Typography component="h2" variant="h3" gutterBottom>
                            Coś poszło nie tak ;(
                        </Typography>
                    </Paper>
                </main>
            );
        }

        return (
            <div>
                <main className={classes.main}>
                    <CssBaseline/>
                    <Paper className={classes.paper}>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper>
                </main>
            </div>
        );
    }
}

CategoryContainer.propTypes = {};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(CategoryContainer));