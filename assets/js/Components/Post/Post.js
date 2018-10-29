import React from 'react';
import withStyles from '@material-ui/core/styles/withStyles';
import {connect} from 'react-redux';
import Paper from '@material-ui/core/Paper';
import Avatar from "@material-ui/core/Avatar/Avatar";
import LockIcon from "@material-ui/core/SvgIcon/SvgIcon";
import Typography from "@material-ui/core/Typography/Typography";
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Button from "@material-ui/core/Button/Button";
import TextField from "@material-ui/core/TextField/TextField";
import axios from './../../axios';
import {withSnackbar} from "notistack";
import Tabs from '@material-ui/core/Tabs';
import Tab from '@material-ui/core/Tab';
import UploadThumbnailForm from './UploadThumbnailForm';
import SelectCategory from './SelectCategory';

const styles = theme => ({
    root: {
        flexGrow: 1,
    },
    grow: {
        flexGrow: 1,
    },
    menuButton: {
        marginLeft: -12,
        marginRight: 20,
        color: '#fff',
        textdecoration: '0'
    },
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: '100%',
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
});

let data;

function TabContainer(props) {
    return (
        <Typography component="div" style={{padding: 8 * 3}}>
            {props.children}
        </Typography>
    );
}

class Post extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            content: '',
            title: '',
            data: '',
            category: '',
            titleError: false,
            value: 'one'
        };

    }

    handleChange = (event, value) => {
        this.setState({
            value
        })
    };

    handleChangeAvatar = (data) => {
        this.setState({
            data
        });
    };

    handleChangeCategory = (category) => {
        console.log(category);
        this.setState({category});
    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <div className={classes.root}>
                    <Paper className={classes.paper}>
                        <Typography variant="headline">Dodaj post</Typography>
                        <Tabs value={this.state.value} onChange={this.handleChange}>
                            <Tab value="one" label="Post z innej strony"/>
                            <Tab value="two" label="Własny post"/>
                        </Tabs>
                        {this.state.value === 'one' && <TabContainer>
                            <TextField
                                error={this.state.titleError}
                                label="Tytuł"
                                helperText={<span>Pole nie moze być puste</span>}
                                type="text"
                                onChange={(e) => {
                                    this.setState({
                                        title: e.target.value
                                    })
                                }}
                            />
                            <TextField
                                error={this.state.titleError}
                                label="Link"
                                helperText={<span>Pole nie moze być puste</span>}
                                type="text"
                                onChange={(e) => {
                                    this.setState({
                                        content: `<iframe src="${e.target.value}"></iframe>`
                                    })
                                }}
                            />
                            <UploadThumbnailForm handleChangeAvatar={this.handleChangeAvatar}/>
                            <SelectCategory handleChangeCategory={this.handleChangeCategory}/>
                            <Button
                                fullWidth
                                variant="raised"
                                color="secondary"
                                onClick={() => {
                                    if (this.state.title === '') {
                                        this.setState({
                                            titleError: true
                                        })
                                    } else {
                                        if (this.state.content !== '') {
                                            this.state.data.append("content", this.state.content);
                                            this.state.data.append("title", this.state.title);
                                            this.state.data.append("type", 'oder_site');
                                            this.state.data.append("category", this.state.category);

                                            axios.post('/api/v1/post', this.state.data, {
                                                headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                            }).then((response) => {
                                                this.props.onPresentSnackbar('success', 'Post dodano');
                                            })
                                        }
                                    }
                                }}
                            >
                                Dodaj post
                            </Button>
                        </TabContainer>}
                        {this.state.value === 'two' && <TabContainer>
                            <TextField
                                error={this.state.titleError}
                                label="Tytuł"
                                helperText={<span>Pole nie moze być puste</span>}
                                type="text"
                                onChange={(e) => {
                                    this.setState({
                                        title: e.target.value
                                    })
                                }}
                            />
                            <CKEditor
                                editor={ClassicEditor}
                                onInit={editor => {
                                    // You can store the "editor" and use when it is needed.
                                    console.log('Editor is ready to use!', editor);
                                }}
                                data={this.state.content}
                                onChange={(event, editor) => {
                                    data = editor.getData();
                                    this.setState({
                                        content: data
                                    });

                                    console.log({event, editor, data});
                                }}
                            />
                            <UploadThumbnailForm handleChangeAvatar={this.handleChangeAvatar}/>
                            <Button
                                fullWidth
                                variant="raised"
                                color="secondary"
                                onClick={() => {
                                    if (this.state.title === '') {
                                        this.setState({
                                            titleError: true
                                        })
                                    } else {
                                        if (this.state.content !== '') {
                                            this.state.data.append("content", this.state.content);
                                            this.state.data.append("title", this.state.title);
                                            this.state.data.append("type", 'own_post');

                                            axios.post('/api/v1/post', this.state.data, {
                                                headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                            }).then((response) => {
                                                this.props.onPresentSnackbar('success', 'Post dodano');
                                            })
                                        }
                                    }
                                }}
                            >
                                Dodaj post
                            </Button>
                        </TabContainer>}
                    </Paper>
                </div>
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({});

export default connect(mapStateToProps)(withStyles(styles)(withSnackbar(Post)));