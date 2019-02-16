import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import FormControl from '@material-ui/core/FormControl';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Checkbox from '@material-ui/core/Checkbox';
import Input from '@material-ui/core/Input';
import InputLabel from '@material-ui/core/InputLabel';
import LockIcon from '@material-ui/icons/LockOutlined';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';
import AppBar from "@material-ui/core/AppBar";
import Tabs from "@material-ui/core/Tabs";
import SwipeableViews from 'react-swipeable-views';
import Tab from '@material-ui/core/Tab';
import axios from './../../axios/axios';
import PostComponent from "../../components/Home/PostComponent";
import {Link as RouterLink} from "react-router-dom";
import {toast} from "react-toastify";
import CircularProgress from "@material-ui/core/CircularProgress";

function TabContainer({children, dir}) {
    return (
        <Typography component="div" dir={dir} style={{padding: 8 * 3}}>
            {children}
        </Typography>
    );
}

const styles = theme => ({
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
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
    root: {
        backgroundColor: theme.palette.background.paper,
        width: 500,
    },
    button: {
        marginTop: '5em',
    },
    facebook: {
        margin: theme.spacing.unit * 2,
        position: 'relative',
    },
});

class AddPost extends Component {
    state = {
        posts: [],
        limit: 10,
        count: '',
        loading: true,
    };

    componentDidMount = () => {
        this.getAllPost();
    };

    getAllPost = () => {
        this.setState({
            loading: true,
        });
        axios.get(`/api/v1/posts?limit=${this.state.limit}`).then((e) => {
            this.setState({
                posts: e.data.data,
                count: e.data.total,
                loading: false,
            });
        })
            .catch((e) => {
                this.setState({
                    loading: false,
                });
            })
    };


    render() {
        const {classes, theme} = this.props;

        return (
            <main className={classes.main}>
                {this.state.loading === true ?
                    <div className={classes.paper}>
                        <CssBaseline/>
                        <div className={classes.facebook}>
                            <CircularProgress
                                variant="indeterminate"
                                disableShrink
                                size={50}
                                thickness={4}
                            />
                        </div>
                    </div> :
                    <React.Fragment>
                        {this.state.posts.map((post) => {
                            return (
                                <PostComponent thumbnail={post.thumbnail} title={post.title}
                                               shortDescription={post.shortDescription} slug={post.slug}/>
                            )
                        })}
                    </React.Fragment>
                }
                <Button
                    variant="outlined"
                    color="primary"
                    fullWidth
                    className={classes.button}
                    onClick={() => {
                        console.log(this.state.count, this.state.posts.length);
                        if (this.state.count === this.state.posts.length) {
                            toast.info("Nie ma wiecej postów", {
                                position: toast.POSITION.BOTTOM_RIGHT
                            });
                        } else {
                            let limit = this.state.limit + 10;
                            this.setState({limit}, () => {
                                this.getAllPost();
                            });
                        }
                    }}
                >
                    Wiecej
                </Button>
            </main>
        );
    }
}

AddPost.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(styles, {withTheme: true})(AddPost);