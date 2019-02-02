import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import CssBaseline from '@material-ui/core/CssBaseline';
import LockIcon from '@material-ui/icons/LockOutlined';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';
import OwnPostForm from "./Form/OwnPostForm";
import axios from "../../../axios/axios";
import CircularProgress from "@material-ui/core/CircularProgress";

const styles = theme => ({
    main: {
        width: '80%%',
        display: 'block', // Fix IE 11 issue.
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: "80%",
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


class EditPost extends Component {
    constructor(props) {
        super(props);
        this.state = {
            post: [],
            loading: true,
        };
    }

    componentDidMount = () => {
        this.getAllPost();
    };

    getAllPost = () => {
        this.setState({
            loading: true
        });
        axios.get(`/api/v1/post/slug/${this.props.match.params.slug}`).then((e) => {
            this.setState({
                post: e.data,
                loading: false
            });
        })
            .catch((e) => {

            })
    };

    render() {
        const {classes} = this.props;

        return (
            <main className={classes.main}>
                <CssBaseline/>
                {this.state.loading === true ? <Paper className={classes.paper}>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper> :
                    <React.Fragment>
                        <Paper className={classes.paper}>
                            <Avatar className={classes.avatar}>
                                <LockIcon/>
                            </Avatar>
                            <Typography component="h1" variant="h5">
                                Edytuj
                            </Typography>
                            <OwnPostForm title={this.state.post.title} content={this.state.post.content}
                                         category={this.state.post.category} thumbnail={this.state.post.thumbnail}
                                         shortDescription={this.state.post.shortDescription} id={this.state.post.id}
                            />
                            {console.log(this.state.post.thumbnail)}
                        </Paper>
                    </React.Fragment>
                }
            </main>
        );
    }
}

EditPost.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(EditPost);